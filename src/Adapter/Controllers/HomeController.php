<?php

declare(strict_types=1);

namespace App\Adapter\Controllers;

use PDO;
use App\Domain\Repository\LongUrlRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

final class HomeController
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
        $rows = $this->longUrlRepo->countUrlsAndClicks();
        return $this->view->render($response, 'index.html.twig', $rows);
    }
}