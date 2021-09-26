<?php

declare(strict_types=1);

namespace App\Shared\Adapter;

use App\Shared\Adapter\Contracts\Dto;
use App\Shared\Adapter\Exception\InvalidDtoParam;

/**
 * Class DtoBase
 * @author Kilderson Sena <dersonsena@gmail.com>
 */
abstract class DtoBase implements Dto
{
    private array $values = [];

    /**
     * Boundary constructor.
     * @param array $values
     * @throws InvalidDtoParam
     */
    final private function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            if (mb_strstr($key, '_') !== false) {
                $key = lcfirst(str_replace('_', '', ucwords($key, '_')));
            }

            if (!property_exists($this, $key)) {
                throw InvalidDtoParam::forDynamicParam(get_class(), $key);
            }

            $this->{$key} = $value;
            $this->values[$key] = $this->get($key);
        }
    }

    /**
     * Static method to create a Boundary (Input or Output)
     * @param array $values Associative array such as `'property' => 'value'`
     * @throws InvalidDtoParam
     */
    public static function create(array $values): static
    {
        return new static($values);
    }

    /**
     * {@inheritdoc}
     */
    public function values(): array
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidDtoParam
     */
    public function get(string $property)
    {
        return $this->__get($property);
    }

    /**
     * Magic getter method to get a Boundary property value
     * @param string $name
     * @return mixed
     * @throws InvalidDtoParam
     */
    public function __get(string $name)
    {
        $getter = "get" . ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }

        if (!property_exists($this, $name)) {
            throw InvalidDtoParam::forDynamicParam(get_class(), $name);
        }

        return $this->{$name};
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws InvalidDtoParam
     */
    public function __set(string $name, mixed $value)
    {
        throw InvalidDtoParam::forReadonlyProperty(get_class(), $name, $value);
    }

    public function __isset($name): bool
    {
        return property_exists($this, $name);
    }
}
