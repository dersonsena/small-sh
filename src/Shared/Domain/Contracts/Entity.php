<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contracts;

interface Entity
{
    /**
     * Getter do Identifier entity
     * @return int|string
     */
    public function getId();

    /**
     * Method for populate an Entity through array
     * @param array $values Associative array such as `'property' => 'value'`
     * @return void
     */
    public function fill(array $values): void;

    /**
     * Method that contains the property setter logic
     * @param string $property Object property name
     * @param mixed $value Value to be inserted in property
     * @return Entity
     */
    public function set(string $property, $value): Entity;

    /**
     * Method that contains the property getter logic
     * @param string $property Object property name
     * @return mixed
     */
    public function get(string $property);

    /**
     * Output an array based on entity properties
     * @param bool $toSnakeCase
     * @return array
     */
    public function toArray(bool $toSnakeCase = false): array;
}
