<?php

declare(strict_types=1);

use App\Adapter\Repository\Database\DbLongUrlRepository;
use App\Domain\Repository\LongUrlRepository;
use App\Shared\Adapter\Contracts\DatabaseOrm;
use App\Shared\Adapter\Contracts\UuidGenerator;
use App\Shared\Infra\PdoOrm;
use App\Shared\Infra\RamseyUiidAdapter;
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
        DatabaseOrm::class => function (ContainerInterface $c) {
            return new PdoOrm($c->get('db'));
        },
        UuidGenerator::class => function (ContainerInterface $c) {
            return new RamseyUiidAdapter();
        },
        LongUrlRepository::class => function (ContainerInterface $c) {
            return new DbLongUrlRepository($c->get(DatabaseOrm::class), $c->get(RamseyUiidAdapter::class));
        },
    ]);
};
