<?php

namespace Vluzrmos\Precodahora\Models;

trait HasAttributes
{
    protected array $attributes = [];

    public function fill(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function setAttribute($key, $value): static
    {
        if ($method = $this->hasSetMutator($key)) {
            $this->{$method}($value);

            return $this;
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    public function getAttribute($key, $default = null): mixed
    {
        $value = $this->attributes[$key] ?? $default;

        if ($method = $this->hasGetAcessor($key)) {
            $value = $this->{$method}($value);
        }

        return $value;
    }

    public function toArray(): array
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

        if (!array_key_exists($key, $cache)) {
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

    public function __get($key): mixed
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value): void
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
