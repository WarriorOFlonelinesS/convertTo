<?php namespace App\Lib;

use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;

class AppLogger extends \Monolog\Logger
{
    private static $loggers = [];

    public function __construct($key="app", $config = null){
        parent::__construct($key);
        if (empty(($config))) {
            $LOG_PATH = Config::get("LOG_PATH", __DIR__ . "/../../logs");
            $config = [
                'logFile' => "{$LOG_PATH}/{$key}.log",
                "logLevel" => \Monolog\Logger::DEBUG
            ];
        }
        $this->pushHandler(new StreamHandler($config['logFile'], $config['logLevel']));
    }
    public static function getInstance($key="app", $config = null){
        if (empty(self::$loggers[$key])){
            self::$loggers[$key] = new Logger($key, $config);
        }
        return self::$loggers[$key];
    }
    public static function enableSystemLogs(){
        $LOG_PATH = Config::get("LOG_PATH", default: __DIR__.'/../../logs');

        self::$loggers['error'] = new AppLogger('errors');
        self::$loggers['error']->pushHandler(new StreamHandler("{LOG_PATH}/errors.log"));
        ErrorHandler::register(self::$loggers['error']);
        $data = [
            $_SERVER,
            $_REQUEST,
            trim(file_get_contents("php://input"))
        ];
        self::$loggers['request'] = new AppLogger('request');
        self::$loggers['request']->pushHandler(new StreamHandler("{$LOG_PATH}/request.log"));
        self::$loggers['request']->info("REQUEST", $data);
    }
}
