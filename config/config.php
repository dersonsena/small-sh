<?php

return [
    'displayErrorDetails' => !APP_IS_PRODUCTION,
    'baseUrl' => $_ENV['APP_BASE_URL'],
    'session' => [
        'name' => 'smallish',
        'cache_expire' => 0,
        'cookie_httponly' => true,
        'cookie_secure' => APP_IS_PRODUCTION
    ],
    'twig' => [
        'templatePath' => ROOT_PATH . '/templates',
        'cachePath' => APP_IS_PRODUCTION ? ROOT_PATH . '/var/cache' : false
    ],
    'database' => [
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'username' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'dbname' => $_ENV['DB_DATABASE'],
        'charset' => $_ENV['DB_CHARSET']
    ],
];