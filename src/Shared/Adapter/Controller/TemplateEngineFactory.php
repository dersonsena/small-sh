<?php

namespace App\Shared\Adapter\Controller;

use App\Shared\Adapter\Contracts\TemplateEngine;

final class TemplateEngineFactory
{
    private static TemplateEngine $templateEngine;

    private function __construct()
    {
    }

    public static function create(TemplateEngine $templateEngine)
    {
        self::$templateEngine = $templateEngine;
    }

    public static function get(): TemplateEngine
    {
        return self::$templateEngine;
    }
}
