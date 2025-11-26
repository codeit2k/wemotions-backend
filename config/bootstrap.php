<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (!isset($_SERVER['APP_ENV'])) {
    if (file_exists(dirname(__DIR__).'/.env')) {
        (new Dotenv())->usePutenv()->load(dirname(__DIR__).'/.env');
    }
}

if (!isset($_SERVER['APP_ENV'])) {
    throw new \RuntimeException('The "APP_ENV" environment variable is not set. You need to define it in your ".env" file.');
}
