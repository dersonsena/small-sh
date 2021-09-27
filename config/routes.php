<?php

declare(strict_types=1);

use App\Adapter\Controllers\AccessUrlController;
use App\Adapter\Controllers\HomeController;
use App\Adapter\Controllers\ShortenUrlController;
use App\Shared\Adapter\Controller\TemplateEngineFactory;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    TemplateEngineFactory::create($app->getContainer()->get('view'));

    $app->get('/', [HomeController::class, 'execute']);
    $app->get('/{path}', AccessUrlController::class);

    $app->group('/api/public', function (RouteCollectorProxy $group) {
        $group->post('/shorten', ShortenUrlController::class);
    });
};
