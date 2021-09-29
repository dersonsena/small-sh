<?php

declare(strict_types=1);

namespace App\Adapter\Controllers;

use App\Domain\Repository\LongUrlRepository;
use App\Shared\Adapter\Controller\TemplateController;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController extends TemplateController
{
    public function __construct(
        private LongUrlRepository $longUrlRepo
    ) {
    }

    public function handle(Request $request): Response
    {
        $rows = $this->longUrlRepo->countUrlsAndClicks();
        return $this->render('index', $rows);
    }
}
