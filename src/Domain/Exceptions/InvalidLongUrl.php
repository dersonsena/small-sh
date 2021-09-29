<?php

namespace App\Domain\Exceptions;

use App\Shared\Exception\ValidationException;

final class InvalidLongUrl extends ValidationException
{
    public static function forInvalidType(string $fieldName, string $giveType): self
    {
        return new self([$fieldName => 'invalid-type'], "The given URL Type '{$giveType}' is invalid");
    }
}
