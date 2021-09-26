<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contracts;

interface ValueObject
{
    /**
     * Raw value object value
     * @return mixed
     */
    public function value(): mixed;

    /**
     * Method to compare equality between two value objects
     * @param ValueObject $valueObject
     * @return bool
     */
    public function isEqualsTo(ValueObject $valueObject): bool;

    /**
     * Method to return a object as string
     * @return string
     */
    public function __toString(): string;

    /**
     * Method to generate the Value object Hash
     * @return string
     */
    public function objectHash(): string;
}
