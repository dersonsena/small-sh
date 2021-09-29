<?php

declare(strict_types=1);

namespace App\Adapter\Controllers;

use App\Domain\Repository\LongUrlRepository;
use App\Shared\Adapter\Controller\TemplateController;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AccessUrlController extends TemplateController
{
    public function __construct(
        private LongUrlRepository $longUrlRepo
    ) {
    }

    public function handle(Request $request): Response
    {
        $url = $this->longUrlRepo->getUrlByPath($this->args['path']);

        if (is_null($url)) {
            return $this->render('notfound');
        }

        $this->longUrlRepo->registerAccess($url, [
            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
            'REMOTE_PORT' => $_SERVER['REMOTE_PORT'],
            'SERVER_PROTOCOL' => $_SERVER['SERVER_PROTOCOL'],
            'SERVER_NAME' => $_SERVER['SERVER_NAME'],
            'REQUEST_URI' => $_SERVER['REQUEST_URI'],
            'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'],
            'HTTP_HOST' => $_SERVER['HTTP_HOST'],
            'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
            'HTTP_REFERER' => $_SERVER['HTTP_REFERER'],
        ]);

        return $this->redirect($url->longUrl->value());
    }
}
