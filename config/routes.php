<?php

declare(strict_types=1);

use App\Controllers\AccessUrlController;
use App\Controllers\HomeController;
use App\Controllers\ShortenUrlController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', HomeController::class);
    $app->get('/{path}', AccessUrlController::class);

    $app->group('/api/public', function (RouteCollectorProxy $group) {
        $group->post('/shorten', ShortenUrlController::class);
    });
};