<?php

declare(strict_types=1);

namespace App\Domain\UseCase\ShortenUrl;

use App\Shared\Adapter\DtoBase;

/**
 * @property-read string $longUrl
 * @property-read string $type
 * @property-read string $baseUrl
 */
final class InputData extends DtoBase
{
    protected string $longUrl;
    protected string $type;
    protected string $baseUrl;
}