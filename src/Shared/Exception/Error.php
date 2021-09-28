<?php

declare(strict_types=1);

namespace App\Shared\Exception;

interface Error
{
    public function details(): array;
    public function getName(): string;
}
