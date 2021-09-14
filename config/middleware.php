<?php

declare(strict_types=1);

use Slim\App;
use App\Middleware\SessionMiddleware;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->add(SessionMiddleware::class);
    //$app->add(SlimFlashMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app));
};
