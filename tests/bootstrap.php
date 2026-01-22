<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// PHPUnit sets APP_ENV in $_SERVER, but we need it in $_ENV too for bootEnv to respect it
if (isset($_SERVER['APP_ENV'])) {
    $_ENV['APP_ENV'] = $_SERVER['APP_ENV'];
    putenv('APP_ENV=' . $_SERVER['APP_ENV']);
}

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}
