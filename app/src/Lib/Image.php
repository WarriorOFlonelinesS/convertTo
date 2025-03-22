<?php namespace App\Lib;
use App\Lib\File;

  class Image extends File{
    public function __construct($fileName, $data){
      parent::__construct($fileName, $data);
    }

    public function convertFile($to){

      $image = imagecreatefromstring(file_get_contents($this->data)); 
      switch ($to){
        case 'png':
          {
          imagepng($image,  '/var/www/html/public/' . $this->fileName. ".$to"); 
          imagedestroy($image); 
        }
        case 'jpg':
          {
          imagejpeg($image,  '/var/www/html/public/' . $this->fileName. ".$to"); 
          imagedestroy($image); 
        }
      }
  }      

}