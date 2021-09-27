<?php

namespace App\Shared\Domain\Exceptions;

use App\Shared\Exception\ValidationException;

final class InvalidUrlException extends ValidationException
{
    public static function forEmptyUrl(string $fieldName): self
    {
        return new self([$fieldName => 'empty-url'], "URL cannot be empty.");
    }

    public static function forInvalidUrl(string $fieldName, string $giveUrl): self
    {
        return new self([$fieldName => 'invalid-url'], sprintf("The given URL '%s' is invalid", $giveUrl));
    }
}
