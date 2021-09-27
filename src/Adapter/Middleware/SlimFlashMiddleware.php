<?php

namespace App\Adapter\Middleware;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Flash\Messages;

final class SlimFlashMiddleware implements MiddlewareInterface
{
    private SessionInterface $session;
    private Messages $flash;

    public function __construct(SessionInterface $session, Messages $flash)
    {
        $this->session = $session;
        $this->flash = $flash;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->flash->__construct($_SESSION);
        return $handler->handle($request);
    }
}
