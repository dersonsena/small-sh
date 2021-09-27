<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use App\Shared\Exception\ValidationException;

final class InvalidEntityException extends ValidationException
{
    public static function readonlyProperty(string $className, string $propertyName): self
    {
        $message = sprintf(
            "You cannot change the property '%s' of the Entity class '%s' because it is read-only.",
            $propertyName,
            $className
        );

        return new self([$propertyName => 'readonly-property'], $message);
    }

    public static function propertyDoesNotExists(string $className, string $propertyName): self
    {
        $message = sprintf(
            "You cannot get the property '%s' because it doesn't exist in Entity '%s'",
            $propertyName,
            $className
        );

        return new self([$propertyName => 'property-not-found'], $message);
    }
}
