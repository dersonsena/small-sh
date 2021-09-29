<?php

declare(strict_types=1);

namespace App\Shared\Adapter\Controller;

use App\Shared\Adapter\Contracts\TemplateEngine;
use App\Shared\Exception\Error;
use App\Shared\Exception\RuntimeException;
use App\Shared\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

abstract class TemplateController extends ControllerBase
{
    protected TemplateEngine $view;

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
        $this->view = TemplateEngineFactory::get();

        try {
            return $this->handle($this->request)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (ValidationException | RuntimeException $e) {
            return $this->answerError($e);
        }
    }

    private function answerError(Error $e): Response
    {
        $newResponse = $this->render('error', [
            'name' => $e->getName(),
            'message' => $e->getMessage(),
            'details' => $e->details(),
            'stackTrace' => $e->getTraceAsString()
        ])
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(500);

        return $newResponse;
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
}
