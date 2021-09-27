<?php

namespace App\Domain\Exceptions;

use Exception;
use Throwable;

final class InvalidLongUrl extends Exception
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

    public static function forInvalidType(string $giveType, array $details = []): self
    {
        return new self("The given URL Type '{$giveType}' is invalid", $details);
    }

    public function details(): array
    {
        return $this->details();
    }
}