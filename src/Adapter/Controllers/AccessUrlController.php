<?php

declare(strict_types=1);

namespace App\Adapter\Controllers;

use App\Domain\Repository\LongUrlRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

final class AccessUrlController
{
    private Twig $view;
    private LongUrlRepository $longUrlRepo;

    public function __construct(ContainerInterface $container)
    {
        $this->view = $container->get('view');
        $this->longUrlRepo = $container->get(LongUrlRepository::class);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $url = $this->longUrlRepo->getUrlByPath($args['path']);
        
        if (is_null($url)) {
            return $this->view->render($response, 'notfound.html.twig', []);
        }

        $this->longUrlRepo->registerAccess($url, [
            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
            'REMOTE_PORT' => $_SERVER['REMOTE_PORT'],
            'SERVER_NAME' => $_SERVER['SERVER_NAME'],
            'REQUEST_URI' => $_SERVER['REQUEST_URI'],
            'HTTP_HOST' => $_SERVER['HTTP_HOST'],
            'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT']
        ]);

        $newResponse = $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(302)
            ->withHeader('Location', $url->longUrl->value());

        return $newResponse;
    }
}