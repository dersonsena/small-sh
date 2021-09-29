<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Adapter\Controllers\AccessUrlController;
use App\Adapter\Controllers\HomeController;
use App\Adapter\Controllers\ShortenUrlController;
use App\Shared\Adapter\Controller\TemplateEngineFactory;

return function (App $app) {
    TemplateEngineFactory::create($app->getContainer()->get('view'));

    $app->get('/{path}', [AccessUrlController::class, 'execute']);
    $app->get('/', [HomeController::class, 'execute']);

    $app->group('/api/public', function (RouteCollectorProxy $group) {
        $group->post('/shorten', [ShortenUrlController::class, 'execute']);
    });
};
