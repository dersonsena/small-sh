<?php

declare(strict_types=1);

namespace App\Shared\Adapter\Controller;

use App\Shared\Exception\RuntimeException;
use App\Shared\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

abstract class RestController extends ControllerBase
{
    abstract public function handle(Request $request): array;

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

        $this->parseBody();

        try {
            return $this->answerSuccess($this->handle($this->request));
        } catch (ValidationException $e) {
            $meta = [];

            if (!APP_IS_PRODUCTION) {
                $meta = [
                    'name' => $e->getName(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile() . ':' . $e->getLine(),
                    'stackTrace' => $e->getTraceAsString()
                ];
            }

            return $this->answerFail($e->details(), $meta);
        } catch (RuntimeException | Throwable $e) {
            $meta = [];

            if (!APP_IS_PRODUCTION) {
                $meta = [
                    'name' => $e->getName(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile() . ':' . $e->getLine(),
                    'stackTrace' => $e->getTraceAsString()
                ];
            }

            return $this->answerError($e->getMessage(), $meta);
        }
    }

    private function parseBody(): void
    {
        $contentType = $this->request->getHeaderLine('Content-Type');

        if (!strstr($contentType, 'application/json')) {
            return;
        }

        $contents = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }

        $this->request->withParsedBody($contents);
    }

    private function answerSuccess(array $data, int $statusCode = 200, array $meta = []): Response
    {
        $newResponse = $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);

        $newResponse->getBody()->write(json_encode([
            'status' => 'success',
            'data' => $data,
            'meta' => $meta
        ]));

        return $newResponse;
    }

    private function answerFail(array $data, array $meta = []): Response
    {
        $newResponse = $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);

        $newResponse->getBody()->write(json_encode([
            'status' => 'fail',
            'data' => $data,
            'meta' => $meta
        ]));

        return $newResponse;
    }

    private function answerError(string $message, $meta = []): Response
    {
        $newResponse = $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);

        $newResponse->getBody()->write(json_encode([
            'status' => 'error',
            'message' => $message,
            'meta' => $meta
        ]));

        return $newResponse;
    }
}
