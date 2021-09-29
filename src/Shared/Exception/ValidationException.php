<?php

declare(strict_types=1);

namespace App\Shared\Exception;

class ValidationException extends ExceptionBase
{
    public function getName(): string
    {
        return 'Validation Error';
    }
}
