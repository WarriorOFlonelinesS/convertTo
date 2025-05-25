<?php

namespace App\Lib\FileHandlers;
use PhpOffice\PhpWord\Settings;
use App\Lib\Constants;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style;


class Document extends File
{
  public string $extension;
  public function __construct($fileName, $data, $extension)
  {
    parent::__construct($fileName, $data);
    $this->extension = $extension;
  }

  public function convertFile($to)
  {
    if (!$this->getFile($this->extension)) {
      if (!$this->data) {
        throw new \Exception("Failed to open the document!");
      }

      // Set UTF-8 encoding
      mb_internal_encoding('UTF-8');
      
      $phpWord = new PhpWord();
      // Set document properties for better encoding support
      $phpWord->getSettings()->setThemeFontLang('ru-RU');
      $phpWord->getSettings()->setDecimalSymbol(',');
      $phpWord->getSettings()->setListSeparator(';');
      
      $section = $phpWord->addSection();
      
      switch ($this->extension) {
        case 'html': {
          $html = file_get_contents($this->data);
          HTML::addHTML($section, $html, false, false, true); // Last parameter enables UTF-8
          break;
        }
        case 'docx': {
          $phpWord = IOFactory::load($this->data, 'Word2007');
          break;
        }
        case 'odt': {
          $phpWord = IOFactory::load($this->data, 'ODText');
          break;
        }
      }

      $outPath = Constants::OUT_DIR . pathinfo($this->fileName, PATHINFO_FILENAME) . ".$to";

      try {
        switch ($to) {
          case 'docx': {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($outPath);
            break;
          }
          case 'odt': {
            $writer = IOFactory::createWriter($phpWord, 'ODText');
            $writer->save($outPath);
            break;
          }
          case "txt": {
            file_put_contents($outPath, $phpWord, FILE_TEXT | LOCK_EX);
            break;
          }
          case "pdf": {
            $tcpdfPath = realpath(__DIR__ . '/../../../vendor/tecnickcom/tcpdf');
            Settings::setPdfRendererName(Settings::PDF_RENDERER_TCPDF);
            Settings::setPdfRendererPath($tcpdfPath);
            
            
            $phpWord->setDefaultFontName('DejaVu Sans');
            
        
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetFont('dejavusans', '', 12, '', true);
            $pdf->SetAuthor('ConvertTo');
            $pdf->SetTitle(pathinfo($this->fileName, PATHINFO_FILENAME));
            $pdf->SetSubject('Document Conversion');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->setFontSubsetting(true);
            
            // Create PDF writer with custom font settings
            $pdfWriter = IOFactory::createWriter($phpWord, 'PDF', $pdf);
            $pdfWriter->save($outPath);
            break;
          }
          default:
            throw new \Exception("This format isn't supported: " . $to);
        }
      } finally {
        // header("Content-disposition: attachment;filename=$outPath");
        // readfile($outPath);
      }
    }
  }
}

