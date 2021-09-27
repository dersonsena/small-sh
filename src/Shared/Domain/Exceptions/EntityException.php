<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use App\Shared\Domain\Contracts\DomainException as DomainExceptionInterface;
use Exception;
use Throwable;

final class EntityException extends Exception implements DomainExceptionInterface
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

    public static function readonlyProperty(string $className, string $propertyName, array $details = []): self
    {
        return new self(
            "You cannot change the property '{$propertyName}' of the Entity class '{$className}' because it is read-only.",
            $details
        );
    }

    public static function propertyDoesNotExists(string $className, string $propertyName, array $details = []): self
    {
        return new self(
            "You cannot get the property '{$propertyName}' because it doesn't exist in Entity '{$className}'",
            $details
        );
    }

    public function details(): array
    {
        return $this->details;
    }
}
