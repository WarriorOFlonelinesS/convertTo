<?php
require '../vendor/autoload.php';
use App\Lib\App;
use App\Lib\Router;


Router::get('/', function(){
  echo 'It\'s my first API and stamp "Hello, World" :3';

});

Router::post('/save', function($request, $response){

   $data = $request->getJSON();


   if (empty($data)) {
       echo $response->status(400)->toJSON(["error" => "Invalid JSON data"]);
   }

   return $response->toJSON([
       "message" => "Data received successfully",
       "data" => $data
   ]);
});

App::run();