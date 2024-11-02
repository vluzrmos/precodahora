<?php

namespace Vluzrmos\Precodahora\Models;

use ArrayAccess;
use JsonSerializable;

class BaseModel implements JsonSerializable, ArrayAccess
{
    use HasAttributes;

    protected string $keyName;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function getKeyName(): string
    {
        return $this->keyName;
    }

    public function getKey(): mixed
    {
        return $this->getAttribute($this->getKeyName());
    }

    public function is(BaseModel $another)
    {
        return ($this instanceof $another || $another instanceof $this) && $this->getKey() === $another->getKey();
    }
}
