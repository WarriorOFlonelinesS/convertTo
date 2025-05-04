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
            $video->save($format, $outPath);
            break;
          }
          case 'webm': {
            $format = new FFMpeg\Format\Video\WebM('libvorbis', 'libvpx');
            $video->save($format, $outPath);
            break;
          }
          case 'flv': {
            $format = new X264('libmp3lame', 'libx264');
            $format->setAdditionalParameters(['-f', 'flv']);
            $video->save($format, $outPath);
            break;
          }
          case 'avi': {
            $format = new X264('libmp3lame', 'libx264' );
            $format->setAdditionalParameters(['-f', 'avi']);
            $video->save($format, $outPath);
            break;
          }
          case '3gp': {
            $format = new X264('aac', 'libx264' );
            $format->setAdditionalParameters(['-f', '3gp']);
            $video->save($format, $outPath);
            break;
          }
          case 'mkv': {
            $format = new X264('aac', 'libx264');
            $video->save($format, $outPath);
            break;
          }
          case 'mov': {
            $format = new X264('aac', 'libx264');
            $video->save($format, $outPath);
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

