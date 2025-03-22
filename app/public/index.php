<?php
require '../vendor/autoload.php';
use App\Lib\App;
use App\Lib\Image;
use App\Lib\Router;
use App\Lib\Response;
use App\Lib\Request;


Router::get('/', function () {
  echo 'It\'s my first API and stamp "Hello, World" :3';

});

Router::post('/convert/image', function (Request $request, Response $response) {
  if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $data = $_FILES['file']['tmp_name'];
    $to = isset($_GET['to']) ? $_GET['to'] : 'png';
    $file = new Image('test', $data);
    $file->convertFile($to);
    $response->status(201);
    return $response->toJSON(['message' => 'File saved successfully']);
  } else {
    $response->status(500);
    return $response->toJSON(['message' => 'Failed to save file']);
  }
});


App::run();