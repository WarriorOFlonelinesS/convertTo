<?php
require '../vendor/autoload.php';
use App\Lib\App;
use App\Lib\Response;
use App\Lib\Request;
use App\Lib\Router;

Router::get('/', function(){
  echo 'It\'s my first API and stamp "Hello, World" :3';
});



App::run();