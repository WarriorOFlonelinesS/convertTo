<?php
namespace App\Lib\FileHandlers;
use App\Lib\Constants;
use App\Lib\FileHandlers\File;

class Image extends File
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
      
      $image = imagecreatefromstring(file_get_contents($this->data));

      if (!$image) {
        throw new \Exception("Failed to create image from data");
      }

      $outPath = Constants::OUT_DIR . pathinfo($this->fileName, PATHINFO_FILENAME) . ".$to";

      try {
        switch ($to) {
          case 'png': {
            imagepng($image, $outPath);
            break;
          }
          case 'jpg': {
            imagejpeg($image, $outPath);
            break;
          }
          case 'bmp': {
            imagebmp($image, $outPath);
            break;
          }
          case 'gif': {
            imagegif($image, $outPath);
            break;
          }
          default:
            imagedestroy($image);
            throw new \Exception("This format isn't supported: " . $to);
        }

      } finally {
        imagedestroy($image);
      }
    }

  }
}