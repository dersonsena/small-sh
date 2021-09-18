<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', function ($request, $response, $args) {
        return $this->get('view')->render($response, 'index.html.twig', []);
    })->setName('profile');

    $app->group('/api/public', function (RouteCollectorProxy $group) {
        $group->post('/shorten', function (Request $request, Response $response, array $args) {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            $newResponse->withHeader('Content-type', 'application/json');
            $newResponse->withStatus(200);
            $newResponse->getBody()->write(json_encode([
                'status' => 'success',
                'data' => [
                    'huge' => 'https://www.devmedia.com.br/ajax-com-jquery-trabalhando-com-requisicoes-assincronas/37141',
                    'shortened' => 'https://smll.sh/abc123efg',
                    'created_at' => (new DateTimeImmutable())->format(DateTimeInterface::ATOM)
                ]
            ]));
            return $newResponse;
        });
    });
};