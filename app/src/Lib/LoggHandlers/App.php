<?php namespace App\Lib\LoggHandlers;

class App
{
  public static function run(){
    AppLogger::enableSystemLogs();
  }
}