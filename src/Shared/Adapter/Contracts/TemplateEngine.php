<?php

namespace App\Shared\Adapter\Contracts;

use Psr\Http\Message\ResponseInterface as Response;

interface TemplateEngine
{
    public function render(Response $response, string $templateFile, array $params = []): Response;
}
