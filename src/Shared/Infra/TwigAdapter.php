<?php

declare(strict_types=1);

namespace App\Shared\Infra;

use App\Shared\Adapter\Contracts\TemplateEngine;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Odan\Session\SessionInterface;

final class TwigAdapter implements TemplateEngine
{
    private Twig $twig;

    public function __construct(array $config, Messages $flash, SessionInterface $session)
    {
        $this->twig = Twig::create(
            $config['twig']['templatePath'],
            ['cache' => APP_IS_PRODUCTION ? $config['twig']['cachePath'] : false]
        );

        $this->twig->getEnvironment()->addGlobal('baseUrl', $config['baseUrl']);
        $this->twig->getEnvironment()->addGlobal('session', $session);
        $this->twig->getEnvironment()->addGlobal('flash', $flash);
        $this->twig->getEnvironment()->addGlobal('sessionCookieName', $config['session']['name']);
    }

    public function getTwig(): Twig
    {
        return $this->twig;
    }

    public function render(Response $response, string $templateFile, array $params = []): Response
    {
        if (!preg_match("/^.*\.html.twig$/D", $templateFile)) {
            $templateFile .= '.html.twig';
        }

        return $this->twig->render($response, $templateFile, $params);
    }
}
