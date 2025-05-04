<?php namespace App\Lib\FileHandlers;

 abstract class File{
    protected string $fileName;
    protected string $data;

    public function __construct($fileName, $data){
      $this->fileName = $fileName;
      $this->data = $data;
    }

    private function checkFileExtention($extension){
      $dangerousExtensions = ['exe', 'js', 'bat', 'vbs', 'scr', 'lnk', 'dll', 'com', 'cmd'];
      if(in_array($extension, $dangerousExtensions)){
        throw new \Exception('This file has dangerous extension!');
      }
    } 

    
    protected function getFile($extension){
      return $this->checkFileExtention($extension);
    }

    abstract public function convertFile($to);

  }