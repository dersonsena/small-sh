<?php

declare(strict_types=1);

namespace App\Shared\Adapter\Controller;

use App\Shared\Adapter\Contracts\TemplateEngine;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Throwable;

abstract class ControllerBase
{
    private Request $request;
    private Response $response;
    protected array $args;
    protected array $body;
    protected TemplateEngine $view;
    private int $statusCode = 200;

    /**
     * @param Request $request
     * @return Response
     */
    abstract public function handle(Request $request): Response;

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function execute(Request $request, Response $response, array $args = []): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $this->body = $this->parseBody();
        $this->view = TemplateEngineFactory::get();

        try {
            return $this->handle($this->request)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus($this->statusCode);

        } catch (Throwable $e) {
            $this->response->getBody()->write("
                <h1>{$e->getMessage()}</h1>
                <h2>{$e->getFile()}: {$e->getLine()}</h2>
                <pre>{$e->getTraceAsString()}</pre>"
            );

            return $this->response
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(500);
        }
    }

    /**
     * @param TemplateEngine $templateEngine
     * @return ControllerBase
     */
    public function setTemplateEngine(TemplateEngine $templateEngine): self
    {
        $this->view = $templateEngine;
        return $this;
    }

    /**
     * @param string $templatePath
     * @param array $params
     * @return Response
     */
    protected function render(string $templatePath, array $params = []): Response
    {
        return $this->view->render($this->response, $templatePath, $params);
    }

    /**
     * @param string $url
     * @return Response
     */
    protected function redirect(string $url): Response
    {
        return $this->response
            ->withStatus(302)
            ->withHeader('Location', $url);
    }

    /**
     * @return Response
     */
    protected function refresh(): Response
    {
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->request->getMethod() === 'POST';
    }

    /**
     * @return array
     */
    private function parseBody(): array
    {
        $contentType = $this->request->getHeaderLine('Content-Type');

        if (!strstr($contentType, 'application/json')) {
            return [];
        }

        $contents = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        $request = $this->request->withParsedBody($contents);
        return (array)$request->getParsedBody();
    }
}
