<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use App\Shared\Exception\RuntimeException;

final class InvalidEntityException extends RuntimeException
{
    public static function readonlyProperty(string $className, string $propertyName): self
    {
        return new self(sprintf(
            "You cannot change the property '%s' of the Entity class '%s' because it is read-only.",
            $propertyName,
            $className
        ));
    }

    public static function propertyDoesNotExists(string $className, string $propertyName): self
    {
        return new self(sprintf(
            "You cannot get the property '%s' because it doesn't exist in Entity '%s'",
            $propertyName,
            $className
        ));
    }

    public function getName(): string
    {
        return 'Invalid Entity Error';
    }
}
