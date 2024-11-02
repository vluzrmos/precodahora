<?php

namespace Vluzrmos\Precodahora\Queries;

use Vluzrmos\Precodahora\Exceptions\ValidationException;
use Vluzrmos\Precodahora\Models\ErrorBag;

class Query
{
    protected array $params = [];

    protected array $required = [];

    /**
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->params = array_merge($this->params, $params);
    }

    /**
     * @throws ValidationException
     * @return bool
     */
    public function throwValidate(): bool
    {
        return $this->validate(true);
    }

    /**
     * @param bool $throw
     * @throws ValidationException
     * @return bool
     */
    public function validate(bool $throw = false): bool
    {
        $errors = new ErrorBag();

        foreach ($this->required as $key) {
            if (empty($this->params[$key])) {
                $errors->add($key, "Query param {$key} is required");
            }
        }

        $valid = empty($this->required) || $errors->isEmpty();

        if ($throw && !$valid) {
            throw ValidationException::fromErrors($errors);
        }

        return $valid;
    }

    public function setParam(string $key, mixed $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    public function get(string $key)
    {
        return $this->params[$key] ?? null;
    }

    public function all(): array
    {
        return $this->params;
    }

    public function clean(): array
    {
        return array_filter($this->all(), function ($value) {
            return !empty($value);
        });
    }

    /**
     * @throws ValidationException
     * @return string
     */
    public function getQuery()
    {
        $this->throwValidate();

        return http_build_query($this->params);
    }

    public function forgetAttribute(string $key)
    {
        unset($this->params[$key]);

        return $this;
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function __toString()
    {
        return $this->getQuery();
    }
}
