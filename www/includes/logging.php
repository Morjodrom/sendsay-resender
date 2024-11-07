<?php
const LOGGER_MAIN = 'main';

require_once __DIR__ . '/../vendor/autoload.php';

use Monolog\ErrorHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;

defined("IS_DEV") or define("IS_DEV", true);
$LOGS_DIR_PATH = $_SERVER['DOCUMENT_ROOT'] . '/logs/';


$lineFormatter = new LineFormatter(null, 'Y-m-d H:i:s');
$logger = new \Monolog\Logger(LOGGER_MAIN);

// debug logging
$date = date('Y-m-d');
$logDebug = new FilterHandler(
    new RotatingFileHandler(
        $LOGS_DIR_PATH . 'debug.log',
        10
    )
);
$logDebug->setFormatter($lineFormatter);
$logger->pushHandler($logDebug);

// errors logging
$logError = new StreamHandler($LOGS_DIR_PATH . 'errors_' . $date . '.log', \Monolog\Logger::WARNING);
$logError->setFormatter($lineFormatter);
$logger->pushHandler($logError);


if(!IS_DEV){
    ErrorHandler::register($logger);
}

\Monolog\Registry::addLogger($logger);