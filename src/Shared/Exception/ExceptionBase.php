<?php

declare(strict_types=1);

namespace App\Shared\Exception;

use Exception;

abstract class ExceptionBase extends Exception implements Error
{
    protected array $details = [];
    protected int | string $errorCode;

    public function __construct(array $details, string $message = '', ?int $code = 0, Exception $previous = null)
    {
        parent::__construct($message ?: 'Application Error', $code, $previous);
        $this->details = $details;
        $this->code = $this->errorCode ?? $code;
    }

    public function details(): array
    {
        return $this->details;
    }

    public function getName(): string
    {
        return 'Generic Error';
    }
}
