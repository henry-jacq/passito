<?php

declare(strict_types=1);

namespace App\Traits;

use ReflectionProperty;
use ReflectionUnionType;
use BadMethodCallException;
use TypeError;

trait EntityGetSetTrait
{
    public function __call(string $method, array $arguments)
    {
        $prefix = substr($method, 0, 3);
        $property = lcfirst(substr($method, 3));

        if (!property_exists($this, $property)) {
            throw new BadMethodCallException(sprintf('Method %s does not exist.', $method));
        }

        // Handle getters
        if ($prefix === 'get') {
            return $this->$property;
        }

        // Handle setters
        if ($prefix === 'set') {
            $value = $arguments[0] ?? null;

            $ref = new ReflectionProperty($this, $property);
            $type = $ref->getType();

            if ($type !== null) {
                $types = [];

                if ($type instanceof ReflectionUnionType) {
                    foreach ($type->getTypes() as $t) {
                        $types[] = $t->getName();
                    }
                } else {
                    $types[] = $type->getName();
                }

                $allowsNull = $type->allowsNull();
                $valid = false;

                // Null handling
                if ($value === null) {
                    if ($allowsNull) {
                        $this->$property = null;
                        return $this;
                    }
                    $valid = false;
                } else {
                    foreach ($types as $expected) {
                        if (
                            ($expected === 'int' && is_int($value)) ||
                            ($expected === 'string' && is_string($value)) ||
                            ($expected === 'bool' && is_bool($value)) ||
                            ($expected === 'float' && is_float($value)) ||
                            (class_exists($expected) && $value instanceof $expected) ||
                            (interface_exists($expected) && $value instanceof $expected)
                        ) {
                            $valid = true;
                            break;
                        }
                    }
                }

                if (!$valid) {
                    throw new TypeError(sprintf(
                        'Invalid type for %s::%s. Expected %s, got %s.',
                        static::class,
                        $method,
                        implode('|', $types) . ($allowsNull ? '|null' : ''),
                        get_debug_type($value)
                    ));
                }
            }

            $this->$property = $value;
            return $this; // fluent
        }

        throw new BadMethodCallException(sprintf('Method %s does not exist.', $method));
    }
}
