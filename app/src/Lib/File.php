<?php namespace App\Lib;

  class File{
    public string $file_name;
    public $data;

    public function __construct($file_name){
      $this->file_name = $file_name;
    }

    public function saveData(){
      $myfile = fopen($this->file_name, "w");
      fwrite($myfile, 'It is the test!');
      fclose($myfile);
    }
  }