<?php

declare(strict_types=1);

namespace App\Shared\Infra;

use App\Shared\Adapter\Contracts\UuidGenerator;
use Ramsey\Uuid\Uuid;

final class RamseyUiidAdapter implements UuidGenerator
{
    public function create(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function toBytes(string $uuid): string
    {
        return Uuid::fromString($uuid)->getBytes();
    }
}
