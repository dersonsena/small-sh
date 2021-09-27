<?php

declare(strict_types=1);

namespace App\Shared\Adapter\Contracts;

interface UuidGenerator
{
    public function create(): string;
    public function toBytes(string $uuid): string;
}
