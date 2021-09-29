<?php

namespace App\Shared\Domain\Exceptions;

use App\Shared\Exception\RuntimeException;

final class InvalidUrlException extends RuntimeException
{
    public static function forEmptyUrl(): self
    {
        return new self("URL cannot be empty.");
    }

    public static function forInvalidUrl(string $giveUrl): self
    {
        return new self(sprintf("The given URL '%s' is invalid", $giveUrl));
    }

    public function getName(): string
    {
        return 'Invalid URL Error';
    }
}
