<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Odan\Session\Middleware\SessionMiddleware;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use Slim\Flash\Messages;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        SessionInterface::class => function (ContainerInterface $c) {
            $sessionParams = $c->get('config')['session'];
            $session = new PhpSession();
            $session->setOptions($sessionParams);
            return $session;
        },
        SessionMiddleware::class => function (ContainerInterface $c) {
            return new SessionMiddleware($c->get(SessionInterface::class));
        },
        Messages::class => function (ContainerInterface $c) {
            $storage = [];
            return new Messages($storage);
        },
    ]);
};
