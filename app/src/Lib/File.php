<?php namespace App\Lib;

 abstract class File{
    protected string $fileName;
    protected $data;

    public function __construct($fileName, $data){
      $this->fileName = $fileName;
      $this->data = $data;
    }

    abstract public function convertFile($to);

  }