<?php 
namespace App\Lib\FileHandlers;
use App\Lib\Constants;
use App\Lib\FileHandlers\File;
use FFMpeg;
use FFMpeg\Format\Video\X264;

class Video extends File
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
      

      $ffmpeg = FFMpeg\FFMpeg::create();
      $video = $ffmpeg->open($this->data);
    
      if (!$video) {
        throw new \Exception("Failed to open the video!");
      }

      $outPath = Constants::OUT_DIR . pathinfo($this->fileName, PATHINFO_FILENAME) . ".$to";

      
      try {
        switch ($to) {
          case 'mp4': {
            $format = new FFMpeg\Format\Video\X264('libmp3lame', 'libx264');
            $video->save($format, $this->fileName);
            break;
          }
          default:
          throw new \Exception("This format isn't supported: " . $to);
        }
      } finally {
        header("Content-disposition: attachment;filename=$outPath");
        readfile($outPath);
      }
    }

  }
}