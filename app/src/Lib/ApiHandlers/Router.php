<?php namespace App\Lib\ApiHandlers;
class Router
{
  public static function handleCors(){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
      http_response_code(204);
      exit;
    }
  }
  public static function get($route, $callback){
    self::handleCors();
    if (strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0 ){
      return;
    }
    self::on($route, $callback);
  }
  public static function post($route, $callback){
    if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0){
      return;
    }
    self::on($route, $callback);
  }

  public static function on($regex, $cb)
  {
    $params = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $params = (stripos($params, '/') !==0) ? '/' . $params : $params;
    $regex = str_replace('/', '\/', $regex);
    $is_match = preg_match('/^' . $regex . '$/', $params, $matches, PREG_OFFSET_CAPTURE);

    if ($is_match){
      array_shift($matches);
      
      $params = array_map(function ($param){
        return $param[0];
      }, $matches);
     
      $cb( new Response(), new Request($params));
    }
  }
}