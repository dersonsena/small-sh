<?php

declare(strict_types=1);

namespace App\Domain\UseCase\ShortenUrl;

use App\Shared\Adapter\DtoBase;

/**
 * @property-read string $longUrl
 * @property-read string $shortenedUrl
 * @property-read float $economyRate
 * @property-read string $createdAt
 */
final class OutputData extends DtoBase
{
    protected string $longUrl;
    protected string $shortenedUrl;
    protected float $economyRate;
    protected string $createdAt;
}