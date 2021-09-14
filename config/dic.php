<?php

use DI\ContainerBuilder;
use Odan\Session\SessionInterface;
use Slim\Flash\Messages;
use Slim\Views\Twig;

$containerBuilder = new ContainerBuilder();

if (APP_IS_PRODUCTION) {
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

$dependencies = require __DIR__ . '/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

$container->set('config', function() {
    return require __DIR__ . DS . 'config.php';
});

$container->set('view', function () use ($container) {
    $twigConfig = $container->get('config')['twig'];
    $flash = $container->get(Messages::class);
    $session = $container->get(SessionInterface::class);
    return Twig::create($twigConfig['templatePath'], ['cache' => $twigConfig['cachePath']]);
});