<?php
require '../vendor/autoload.php';
use App\Lib\App;
use App\Lib\Router;
use App\Lib\File;
use App\Lib\Response;
use App\Lib\Request;


Router::get('/', function(){
  echo 'It\'s my first API and stamp "Hello, World" :3';

});

Router::post('/save', function(Request $request, Response $response) {
  $data = $request->getJSON()->message;
  $file = new File('test.txt', $data);
  if ($file->saveData()) {
      
      $response->status(201);
      return $response->toJSON(['message' => 'File saved successfully']);
  } else {
      $response->status(500);
      return $response->toJSON(['message' => 'Failed to save file']);
  }
});


App::run();