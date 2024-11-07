<?php

use lib\SendSay;
use Monolog\Registry;

require __DIR__ . '/../includes/all.php';

if(!defined('SENDSAY_TOKEN')) {
    throw new \Exception('SENDSAY_TOKEN must be defined');
}
if(!function_exists('mapToData')) {
    throw new Exception('mapToData function must be defined in the private config file');
}

$input = $_POST;

Registry::getInstance(LOGGER_MAIN)->debug('WEBHOOK INPUT', [
    'input' => $input,
]);


['email' => $email, 'phone' => $phone, 'data' => $data] = mapToData($input);

$sendsay = new SendSay(SENDSAY_TOKEN);
try {
    $sendsay->sendSubscriber($email, $phone, $data);
    $status = 'success';

    Registry::getInstance(LOGGER_MAIN)->debug('SENDSAY SUCCESS');
} catch (JsonException|RuntimeException $e) {
    Registry::getInstance(LOGGER_MAIN)->error('SENDSAY ERROR ' . $e->getMessage(), [
        'exception' => $e,
        'input' => $input
    ]);

    $status = 'error';
}

header('Content-Type: application/json');
echo json_encode([
    'status' => $status,
    'validation' => [
        'email' => $email ? 'ok' : 'empty',
        'phone' => $phone ? 'ok' : 'empty',
    ]
]);