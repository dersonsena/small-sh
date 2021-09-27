<?php

use App\Shared\Infra\TwigAdapter;
use DI\ContainerBuilder;
use Odan\Session\SessionInterface;
use Slim\Flash\Messages;

$containerBuilder = new ContainerBuilder();

$dependencies = require __DIR__ . '/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

$container->set('config', function () {
    return require __DIR__ . DS . 'config.php';
});

$container->set('view', function () use ($container) {
    $config = $container->get('config');
    $flash = $container->get(Messages::class);
    $session = $container->get(SessionInterface::class);
    return new TwigAdapter($config, $flash, $session);
});

$container->set('db', function () use ($container) {
    $dbConfig = $container->get('config')['database'];
    try {
        return new PDO(
            sprintf('mysql:host=%s;dbname=%s', $dbConfig['host'], $dbConfig['dbname']),
            $dbConfig['username'],
            $dbConfig['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );

    } catch (PDOException $e) {
        print "Database Error: " . $e->getMessage();
        die;
    }
});
