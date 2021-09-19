<?php

use DI\ContainerBuilder;
use Slim\Views\Twig;

$containerBuilder = new ContainerBuilder();

$dependencies = require __DIR__ . '/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

$container->set('config', function() {
    return require __DIR__ . DS . 'config.php';
});

$container->set('view', function () use ($container) {
    $twigConfig = $container->get('config')['twig'];
    // $flash = $container->get(Messages::class);
    // $session = $container->get(SessionInterface::class);
    $twig = Twig::create($twigConfig['templatePath'], ['cache' => $twigConfig['cachePath']]);
    $twig->getEnvironment()->addGlobal('baseUrl', $container->get('config')['baseUrl']);
    return $twig;
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