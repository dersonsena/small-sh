<?php

declare(strict_types=1);

namespace App\Adapter\Controllers;

use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

final class HomeController
{
    private PDO $db;
    private Twig $view;

    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get('db');
        $this->view = $container->get('view');
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $stmt = $this->db->prepare(trim("
            select count(*) as total_urls from urls
            union all
            select count(*) as total_clicks from urls_logs
        "));

        $stmt->execute();

        [$totalUrls, $totalClicks] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $this->view->render($response, 'index.html.twig', [
            'totalUrls' => ceil($totalUrls),
            'totalClicks' => ceil($totalClicks)
        ]);
    }
}