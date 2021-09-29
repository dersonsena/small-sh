<?php

declare(strict_types=1);

namespace App\Shared\Adapter\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class ControllerBase
{
    protected Request $request;
    protected Response $response;
    protected array $args;

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->request->getMethod() === 'POST';
    }
}
