<?php

declare(strict_types=1);

namespace App\Controllers;

use PDO;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Ramsey\Uuid\Uuid;

final class ShortenUrlController
{
    private array $config;
    private PDO $db;

    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get('db');
        $this->config = $container->get('config');
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $contents = $request->getParsedBody();

        if (empty($contents) || !array_key_exists('huge_url', $contents)) {
            $newResponse = $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(400);

            $newResponse->getBody()->write(json_encode([
                'status' => 'fail',
                'data' => ['huge_url' => 'missing-param']
            ]));

            return $newResponse;
        }

        if (empty($contents['huge_url'])) {
            $newResponse = $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(400);

            $newResponse->getBody()->write(json_encode([
                'status' => 'fail',
                'data' => ['huge_url' => 'empty-value']
            ]));

            return $newResponse;
        }

        if (filter_var($contents['huge_url'], FILTER_VALIDATE_URL) === false) {
            $newResponse = $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(400);

            $newResponse->getBody()->write(json_encode([
                'status' => 'fail',
                'data' => ['huge_url' => 'invalid-url']
            ]));

            return $newResponse;
        }

        $stmt = $this->db->prepare(trim("
                INSERT INTO `urls` (`id`, `uuid`, `long_url`, `short_url_path`, `created_at`)
                VALUES (:id, :uuid, :long_url, :short_url_path, :created_at)
            "));

        $shortUrlPath = substr(sha1(uniqid((string)rand(), true)), 0, 10);
        $uuid = Uuid::uuid4();
        $createdAt = new DateTimeImmutable();

        $stmt->execute([
            'id' => $uuid->getBytes(),
            'uuid' => $uuid->toString(),
            'long_url' => $contents['huge_url'],
            'short_url_path' => $shortUrlPath,
            'created_at' => $createdAt->format('Y-m-d H:i:s')
        ]);

        $newResponse = $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);

        $newResponse->getBody()->write(json_encode([
            'status' => 'success',
            'data' => [
                'huge' => $contents['huge_url'],
                'shortened' => $this->config['baseUrl'] . '/' . $shortUrlPath,
                'created_at' => $createdAt->format(DateTimeInterface::ATOM)
            ]
        ]));

        return $newResponse;
    }
}