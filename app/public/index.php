<?php
require '../vendor/autoload.php';
use App\Lib\Constants;
use App\Lib\LoggHandlers\App;
use App\Lib\FileHandlers\Image;
use App\Lib\ApiHandlers\Router;
use App\Lib\ApiHandlers\Response;

Router::get('/', fn(Response $response): int => print ($response->toJSON(['message' => 'Welcome to ConvertTo API', 'version' => '1.0.0', 'status' => 'API is running', 'timestamp' => time()])));

Router::post('/convert/image', function (Response $response) {
  try {

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
      return $response->status(400)->toJSON(['message' => 'No file uploaded or upload error occurred']);
    }
    var_dump($_FILES);
    $uploadData = $_FILES['file']['tmp_name'];
    $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $to = isset($_GET['to']) ? $_GET['to'] : '';
    $fileName = basename($_FILES['file']['name'], "." . $fileExtension);
    $filePath = basename($_FILES['file']['name']);
    $targetPath = Constants::UPLOAD_DIR . $filePath;

    if (!move_uploaded_file($uploadData, Constants::UPLOAD_DIR . $filePath)) {
      throw new \Exception;
    }

    $file = new Image($fileName, $targetPath, $fileExtension);
    $file->convertFile($to);
    $response->status(201);
    return $response->toJSON(['message' => 'File converted and saved successfully']);
  } catch (\Exception $e) {

    $response->status(500);
    return $response->toJSON(['message' => $e->getMessage()]);
  }

});

App::run();