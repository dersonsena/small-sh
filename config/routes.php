<?php

declare(strict_types=1);

use Slim\App;

return function (App $app) {
    $app->get('/hello', function ($request, $response, $args) {
        return $this->get('view')->render($response, 'hello.html.twig', []);
    })->setName('profile');
};
