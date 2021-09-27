<?php

declare(strict_types=1);

use Slim\App;
use App\Shared\Adapter\Middleware\SessionMiddleware;
use App\Shared\Adapter\Middleware\SlimFlashMiddleware;
use App\Shared\Adapter\Middleware\TwigMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->add(SessionMiddleware::class);
    $app->add(SlimFlashMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app));
};
