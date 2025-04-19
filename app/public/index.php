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

    if (!isset($_FILES['file'])) {
      return $response->status(400)->toJSON(['message' => 'No file uploaded or upload error occurred']);
    }
    $to = isset($_GET['to']) ? $_GET['to'] : '';
    foreach ($_FILES['file']['tmp_name'] as $key => $upload_data) {


      if ($_FILES['file']['error'][$key] !== UPLOAD_ERR_OK) {
        return $response->status(400)->toJSON(['message' => 'No file uploaded or upload error occurred']);
      }

      $uploadData = $_FILES['file']['tmp_name'][$key];
      $fileExtension = pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
      $fileName = basename($_FILES['file']['name'][$key], "." . $fileExtension);
      $filePath = basename($_FILES['file']['name'][$key]);
      $targetPath = Constants::UPLOAD_DIR . $filePath;

      if (!move_uploaded_file($uploadData, Constants::UPLOAD_DIR . $filePath)) {
        throw new \Exception;
      }

      (new Image($fileName, $targetPath, $fileExtension))->convertFile($to);

    }
    return $response->status(201)->toJSON(['message' => 'File converted and saved successfully']);
  } catch (\Exception $e) {

    return $response->status(500)->toJSON(['message' => $e->getMessage()]);
  }

});

App::run();