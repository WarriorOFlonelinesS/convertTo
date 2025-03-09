<?php namespace App\Lib;

  class File{
    public string $file_name;
    public $data;

    public function __construct($file_name, $data){
      $this->file_name = $file_name;
      $this->data = $data;

    }

    public function saveData(){
      $myfile = fopen($this->file_name, "w");
      fwrite($myfile, $this->data);
      fclose($myfile);
    }
  }