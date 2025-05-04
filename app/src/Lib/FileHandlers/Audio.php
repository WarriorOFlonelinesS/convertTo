<?php
namespace App\Lib\FileHandlers;
use App\Lib\Constants;
use App\Lib\FileHandlers\File;
use FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Audio\Wav;
use FFMpeg\Format\Audio\Vorbis;
use FFMpeg\Format\Audio\Aac;
use FFMpeg\Format\Audio\Flac;

class Audio extends File
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
      $audio = $ffmpeg->open($this->data);

      if (!$audio) {
        throw new \Exception("Failed to open the audio!");
      }
      var_dump(file_exists($this->data));
      $outPath = Constants::OUT_DIR . pathinfo($this->fileName, PATHINFO_FILENAME) . ".$to";
      try {
        switch ($to) {
          case 'mp3': {
            $format = new Mp3();
            $audio->save($format, $outPath);
            break;
          }
          case 'wav': {
            $format = new Wav();
            $audio->save($format, $outPath);
            break;
          }
          case 'ogg': {
            $format = new Vorbis();
            $audio->save($format, $outPath);
            break;
          }
          case 'aac': {
            $format = new Aac();
            $audio->save($format, $outPath);
            break;
          }
          case 'flac': {
            $format = new Flac();
            $audio->save($format, $outPath);
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

