<?php

declare(strict_types=1);

namespace App\Adapter\Controllers;

use App\Domain\UseCase\ShortenUrl\InputData;
use App\Domain\UseCase\ShortenUrl\ShortenUrl;
use App\Domain\ValueObject\LongUrlType;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ShortenUrlController
{
    private array $config;
    private ShortenUrl $useCase;

    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->get('config');
        $this->useCase = $container->get(ShortenUrl::class);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $contents = $request->getParsedBody();

        if (empty($contents) || !array_key_exists('long_url', $contents)) {
            $newResponse = $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(400);

            $newResponse->getBody()->write(json_encode([
                'status' => 'fail',
                'data' => ['long_url' => 'missing-param']
            ]));

            return $newResponse;
        }

        if (empty($contents['long_url'])) {
            $newResponse = $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(400);

            $newResponse->getBody()->write(json_encode([
                'status' => 'fail',
                'data' => ['long_url' => 'empty-value']
            ]));

            return $newResponse;
        }

        $result = $this->useCase->execute(InputData::create([
            'longUrl' => $contents['long_url'],
            'type' => LongUrlType::TYPE_RANDOM,
            'baseUrl' => $this->config['baseUrl'],
        ]));

        $newResponse = $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);

        $newResponse->getBody()->write(json_encode([
            'status' => 'success',
            'data' => $result->values()
        ]));

        return $newResponse;
    }
}
