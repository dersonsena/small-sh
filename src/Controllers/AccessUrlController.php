<?php

declare(strict_types=1);

namespace App\Controllers;

use DateTimeImmutable;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Ramsey\Uuid\Uuid;

final class AccessUrlController
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        /** @var PDO $db */
        $db = $this->container->get('db');
        $stmt = $db->prepare("SELECT `id`, `long_url` FROM `urls` WHERE `short_url_path` = :path");
        $stmt->execute(['path' => $args['path']]);
        $row = $stmt->fetch();

        if (!$row) {
            return $this->container->get('view')->render($response, 'notfound.html.twig', []);
        }

        $sql = "INSERT INTO `urls_logs` (`id`, `uuid`, `url_id`, `created_at`, `meta`) VALUES (:id, :uuid, :url_id, :created_at, :meta)";
        $stmt = $db->prepare($sql);

        $uuid = Uuid::uuid4();

        $stmt->execute([
            'id' => $uuid->getBytes(),
            'uuid' => $uuid->toString(),
            'url_id' => $row['id'],
            'created_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            'meta' => json_encode([
                'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
                'REMOTE_PORT' => $_SERVER['REMOTE_PORT'],
                'SERVER_NAME' => $_SERVER['SERVER_NAME'],
                'REQUEST_URI' => $_SERVER['REQUEST_URI'],
                'HTTP_HOST' => $_SERVER['HTTP_HOST'],
                'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT']
            ]),
        ]);

        $newResponse = $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(302)
            ->withHeader('Location', $row['long_url']);

        return $newResponse;
    }
}