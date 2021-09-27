<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Contracts\Entity;
use App\Shared\Domain\Exceptions\EntityException;
use DateTimeInterface;
use ReflectionClass;
use ReflectionException;

/**
 * Class Entity
 * @package App\Shared\Domain
 * @author Kilderson Sena <dersonsena@gmail.com>
 *
 * @property-read int|string $id
 */
abstract class EntityBase implements Entity
{
    protected string | int | null $id = null;

    /**
     * Entity constructor.
     * @param array $values
     * @throws EntityException
     */
    final private function __construct(array $values)
    {
        $this->fill($values);
    }

    /**
     * Static method to create an Entity
     * @param array $values
     * @return EntityBase
     * @throws EntityException
     */
    public static function create(array $values): self
    {
        return new static($values);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     * @throws EntityException
     */
    public function fill(array $values): void
    {
        foreach ($values as $attribute => $value) {
            $this->set($attribute, $value);
        }
    }

    /**
     * @inheritDoc
     * @throws EntityException
     */
    public function set(string $property, $value): Entity
    {
        if (mb_strstr($property, '_') !== false) {
            $property = lcfirst(str_replace('_', '', ucwords($property, '_')));
        }

        $setter = 'set' . str_replace('_', '', ucwords($property, '_'));

        if (method_exists($this, $setter)) {
            $this->{$setter}($value);
            return $this;
        }

        if (!property_exists($this, $property)) {
            $className = get_class();
            throw EntityException::readonlyProperty($className, $property, [
                'className' => $className,
                'property' => $property,
                'value' => $value
            ]);
        }

        $this->{$property} = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $property)
    {
        return $this->{$property};
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function toArray(bool $toSnakeCase = false): array
    {
        $props = [];
        $propertyList = get_object_vars($this);

        /** @var int|string|object $value */
        foreach ($propertyList as $prop => $value) {
            if ($value instanceof DateTimeInterface) {
                $propertyList[$prop] = $value->format(DATE_ATOM);
                continue;
            }

            if (is_object($value)) {
                $reflectObject = new ReflectionClass(get_class($value));
                $properties = $reflectObject->getProperties();
                $propertyList[$prop] = [];

                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $propertyList[$prop][$property->getName()] = $property->getValue($value);
                }
            }
        }

        $propertyList = json_decode(json_encode($propertyList), true);

        foreach ($propertyList as $name => $value) {
            if ($toSnakeCase) {
                $name = mb_strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
            }

            $props[$name] = $value;
        }

        return $props;
    }

    /**
     * Magic getter method to get an Entity property value
     * @param string $name
     * @return mixed
     * @throws EntityException
     */
    public function __get(string $name)
    {
        $getter = "get" . ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }

        if (!property_exists($this, $name)) {
            $className = get_class();
            throw EntityException::propertyDoesNotExists($className, $name, [
                'className' => $className,
                'propertyName' => $name
            ]);
        }

        return $this->{$name};
    }

    /**
     * @param mixed $value
     * @throws EntityException
     */
    public function __set(string $name, $value)
    {
        $className = get_class();
        throw EntityException::readonlyProperty($className, $name, [
            'className' => $className,
            'property' => $name,
            'value' => $value
        ]);
    }
}
