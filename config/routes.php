<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Ramsey\Uuid\Uuid;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response, array $args) {
        return $this->get('view')->render($response, 'index.html.twig', []);
    });

    $app->get('/{path}', function (Request $request, Response $response, array $args) {
        /** @var PDO $db */
        $db = $this->get('db');
        $stmt = $db->prepare("SELECT `id`, `long_url` FROM `urls` WHERE `short_url_path` = :path");
        $stmt->execute(['path' => $args['path']]);
        $row = $stmt->fetch();

        /*if (!$row) {
            //
        }*/

        $sql = "INSERT INTO `urls_logs` (`id`, `uuid`, `url_id`, `created_at`, `meta`) VALUES (:id, :uuid, :url_id, :created_at, :meta)";
        $stmt = $db->prepare($sql);

        $uuid = Uuid::uuid4();
        $createdAt = new DateTimeImmutable();

        $stmt->execute([
            'id' => $uuid->getBytes(),
            'uuid' => $uuid->toString(),
            'url_id' => $row['id'],
            'created_at' => $createdAt->format('Y-m-d H:i:s'),
            'meta' => json_encode([
                'REMOTE_ADDR' => filter_input(INPUT_SERVER, 'REMOTE_ADDR'),
                'REMOTE_PORT' => filter_input(INPUT_SERVER, 'REMOTE_PORT'),
                'SERVER_NAME' => filter_input(INPUT_SERVER, 'SERVER_NAME'),
                'REQUEST_URI' => filter_input(INPUT_SERVER, 'REQUEST_URI'),
                'HTTP_HOST' => filter_input(INPUT_SERVER, 'HTTP_HOST'),
                'HTTP_USER_AGENT' => filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'),
            ]),
        ]);

        $newResponse = $response->withHeader('Location', $row['long_url'])
            ->withStatus(302);

        return $newResponse;
    });

    $app->group('/api/public', function (RouteCollectorProxy $group) {
        $group->post('/shorten', function (Request $request, Response $response, array $args) {
            /** @var PDO $db */
            $db = $this->get('db');

            $sql = "
                INSERT INTO `urls` (`id`, `uuid`, `long_url`, `short_url_path`, `created_at`)
                VALUES (:id, :uuid, :long_url, :short_url_path, :created_at)
            ";
            $stmt = $db->prepare($sql);

            $contents = $request->getParsedBody();
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

            $newResponse = $response->withHeader('Content-type', 'application/json');
            $newResponse->withHeader('Content-type', 'application/json');
            $newResponse->withStatus(200);
            $newResponse->getBody()->write(json_encode([
                'status' => 'success',
                'data' => [
                    'huge' => $contents['huge_url'],
                    'shortened' => 'https://smll.sh/' . $shortUrlPath,
                    'created_at' => $createdAt->format(DateTimeInterface::ATOM)
                ]
            ]));

            return $newResponse;
        });
    });
};