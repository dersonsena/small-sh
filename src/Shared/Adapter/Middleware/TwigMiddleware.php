<?php

declare(strict_types=1);

namespace App\Shared\Adapter\Middleware;

use App\Shared\Infra\TwigAdapter;
use RuntimeException;
use Slim\App;
use Slim\Views\Twig;

final class TwigMiddleware extends \Slim\Views\TwigMiddleware
{
    public static function createFromContainer(App $app, string $containerKey = 'view'): self
    {
        $container = $app->getContainer();

        if ($container === null) {
            throw new RuntimeException('The app does not have a container.');
        }

        if (!$container->has($containerKey)) {
            throw new RuntimeException(
                "The specified container key does not exist: $containerKey"
            );
        }

        /** @var TwigAdapter $twigAdapter */
        $twigAdapter = $container->get($containerKey);

        if (!($twigAdapter->getTwig() instanceof Twig)) {
            throw new RuntimeException(
                "Twig instance could not be resolved via container key: $containerKey"
            );
        }

        return new self(
            $twigAdapter->getTwig(),
            $app->getRouteCollector()->getRouteParser(),
            $app->getBasePath()
        );
    }
}