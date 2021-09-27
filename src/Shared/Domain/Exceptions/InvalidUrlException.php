<?php

namespace App\Shared\Domain\Exceptions;

use App\Shared\Domain\Contracts\DomainException as DomainExceptionInterface;
use Exception;
use Throwable;

final class InvalidUrlException extends Exception implements DomainExceptionInterface
{
    protected array $details = [];

    private function __construct(
        string $message = 'Domain Exception',
        array $details = [],
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->message = $message;
        $this->details = $details;
        parent::__construct($this->message, $code, $previous);
    }

    public static function forEmptyUrl(): self
    {
        return new self("URL cannot be empty.");
    }

    public static function forInvalidUrl(string $giveUrl, array $details = []): self
    {
        return new self("The given URL '{$giveUrl}' is invalid", $details);
    }

    public function details(): array
    {
        return $this->details();
    }
}
