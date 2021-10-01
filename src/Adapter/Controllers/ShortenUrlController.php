<?php

declare(strict_types=1);

namespace App\Adapter\Controllers;

use App\Domain\UseCase\ShortenUrl\InputData;
use App\Domain\UseCase\ShortenUrl\ShortenUrl;
use App\Domain\ValueObject\LongUrlType;
use App\Shared\Adapter\Controller\RestController;
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

        $result = $this->useCase->execute(InputData::create([
            'longUrl' => $contents['long_url'] ?? '',
            'type' => LongUrlType::TYPE_RANDOM,
            'baseUrl' => $this->config['baseUrl'],
        ]));

        return $result->values();
    }
}
