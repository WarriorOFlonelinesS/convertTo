<?php
namespace App\Lib\FileHandlers;
use App\Lib\Constants;
use PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;


class Document extends File
{
  public string $extension;
  public function __construct($fileName, $data, $extension)
  {
    parent::__construct($fileName, $data, );
    $this->extension = $extension;
  }

  public function convertFile($to)
  {



    if (!$this->getFile($this->extension)) {


      if (!$this->data) {
        throw new \Exception("Failed to open the document!");
      }
      $phpWord = new PhpWord();
      $section = $phpWord->addSection();
      switch ($this->extension) {
        case 'html': {
 
          $html = file_get_contents($this->data);
          var_dump($html);
          HTML::addHTML($section, $html);
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

