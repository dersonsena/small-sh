<?php

declare(strict_types=1);

namespace App\Shared\Adapter\Exception;

use App\Shared\Exception\ValidationException;

final class InvalidDtoParam extends ValidationException
{
    public static function forDynamicParam(string $className, string $property): self
    {
        $message = sprintf(
            "It couldn't construct DTO '%s' because the property '%s' doesn't exist",
            $className,
            $property
        );

        return new self(['property' => 'invalid-param'], $message);
    }

    public static function forReadonlyProperty(string $className, string $property): self
    {
        $message = sprintf(
            "You cannot change the property '%s' of the DTO class '%s' because it is read-only.",
            $property,
            $className
        );

        return new self(['property' => 'readonly-property'], $message);
    }
}
