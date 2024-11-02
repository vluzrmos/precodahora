<?php

namespace Vluzrmos\Precodahora\Models;

use ArrayAccess;
use JsonSerializable;

class BaseModel implements JsonSerializable, ArrayAccess
{
    protected array $attributes = [];

    protected string $keyName;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function setAttribute($key, $value)
    {
        if ($method = $this->hasSetMutator($key)) {
            $this->{$method}($value);

            return $this;
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    public function getAttribute($key, $default = null)
    {
        $value = $this->attributes[$key] ?? $default;

        if ($method = $this->hasGetAcessor($key)) {
            $value = $this->{$method}($value);
        }

        return $value;
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    protected function normalizeKey(string $key)
    {
        static $cache = [];

        if (array_key_exists($key, $cache)) {
            $cache[$key] = str_replace(' ', '', ucwords(trim(str_replace(['_', '-'], ' ', strtolower($key)))));
        }

        return $cache[$key];
    }

    protected function hasSetMutator($key): false|string
    {
        $method = 'set' . $this->normalizeKey($key) . 'Attribute';
        
        return method_exists($this, $method) ? $method : false;
    }

    protected function hasGetAcessor($key): false|string
    {
        $method = 'get' . $this->normalizeKey($key) . 'Attribute';

        return method_exists($this, $method) ? $method : false;
    }

    public function getKeyName()
    {
        return $this->keyName;
    }

    public function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }

    public function is(BaseModel $another)
    {
        return ($this instanceof $another || $another instanceof $this) && $this->getKey() === $another->getKey();
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]) || $this->hasGetAcessor($offset);
    }

    public function offsetGet($offset): mixed
    {
        return $this->getAttribute($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->setAttribute($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

    public function __isset($name): bool
    {
        return $this->offsetExists($name);
    }

    public function __unset($name): void
    {
        $this->offsetUnset($name);
    }
}
