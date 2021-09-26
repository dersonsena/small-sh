<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contracts;

interface DomainException
{
    public function details(): array;
}
