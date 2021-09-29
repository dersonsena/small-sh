<?php

declare(strict_types=1);

namespace App\Adapter\Controllers;

use App\Domain\UseCase\ShortenUrl\InputData;
use App\Domain\UseCase\ShortenUrl\ShortenUrl;
use App\Domain\ValueObject\LongUrlType;
use App\Shared\Adapter\Controller\RestController;
use App\Shared\Exception\ValidationException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface as Request;

final class ShortenUrlController extends RestController
{
    private array $config;
    private ShortenUrl $useCase;

    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->get('config');
        $this->useCase = $container->get(ShortenUrl::class);
    }

    public function handle(Request $request): array
    {
        $contents = $request->getParsedBody();

        if (empty($contents) || !array_key_exists('long_url', $contents)) {
            throw new ValidationException(['long_url' => 'missing-param']);
        }

        if (empty($contents['long_url'])) {
            throw new ValidationException(['long_url' => 'empty-value']);
        }

        if (!filter_var($contents['long_url'], FILTER_VALIDATE_URL)) {
            throw new ValidationException(['long_url' => 'invalid-url']);
        }

        $result = $this->useCase->execute(InputData::create([
            'longUrl' => $contents['long_url'],
            'type' => LongUrlType::TYPE_RANDOM,
            'baseUrl' => $this->config['baseUrl'],
        ]));

        return $result->values();
    }
}
