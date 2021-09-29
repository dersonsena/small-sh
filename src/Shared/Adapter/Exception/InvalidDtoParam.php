<?php

declare(strict_types=1);

namespace App\Shared\Adapter\Exception;

use App\Shared\Exception\RuntimeException;

final class InvalidDtoParam extends RuntimeException
{
    public static function forDynamicParam(string $className, string $property): self
    {
        return new self(sprintf(
            "It couldn't construct DTO '%s' because the property '%s' doesn't exist",
            $className,
            $property
        ));
    }

    public static function forReadonlyProperty(string $className, string $property): self
    {
        return new self(sprintf(
            "You cannot change the property '%s' of the DTO class '%s' because it is read-only.",
            $property,
            $className
        ));
    }
}
