<?php

namespace Tests\YooKassa\Validator\Fixtures;

use ArrayAccess;

class ArrayAccessClass implements ArrayAccess {
    private array $container = [];

    public function __construct()
    {
        $this->container = [
            "one"   => 1,
            "two"   => 2,
            "three" => 3,
        ];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }
}