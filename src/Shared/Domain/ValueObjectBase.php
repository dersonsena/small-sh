<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Contracts\ValueObject;
use ReflectionClass;
use ReflectionException;

abstract class ValueObjectBase implements ValueObject
{
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->value();
    }

    /**
     * @inheritDoc
     */
    public function isEqualsTo(ValueObject $valueObject): bool
    {
        return $this->objectHash() === $valueObject->objectHash();
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function objectHash(): string
    {
        $reflectObject = new ReflectionClass(get_class($this));
        $props = $reflectObject->getProperties();
        $value = '';

        foreach ($props as $prop) {
            $prop->setAccessible(true);
            $value .= $prop->getValue($this);
        }

        return md5($value);
    }
}
