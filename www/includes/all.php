<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/logging.php';

$configPath = __DIR__ . '/../private/config.php';
if(file_exists($configPath)) {
    require $configPath;
} else {
    throw new Exception("Config file doesn't exist. Place it in " . $configPath);
}