<?php

declare(strict_types=1);

use App\Controllers\AccessUrlController;
use App\Controllers\ShortenUrlController;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response, array $args) {
        return $this->get('view')->render($response, 'index.html.twig', []);
    });

    $app->get('/{path}', AccessUrlController::class);

    $app->group('/api/public', function (RouteCollectorProxy $group) {
        $group->post('/shorten', ShortenUrlController::class);
    });
};