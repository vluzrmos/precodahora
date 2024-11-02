<?php

namespace Vluzrmos\Precodahora\Queries;

use Vluzrmos\Precodahora\Exceptions\QueryParamRequiredException;

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

    public function throwValidate()
    {
        return $this->validate(true);
    }

    public function validate($throw = false)
    {
        foreach ($this->required as $key) {
            if (empty($this->params[$key])) {
                if ($throw) {
                    throw new \InvalidArgumentException("Query Param {$key} is required");
                }

                return false;
            }
        }

        return !empty($this->required);
    }

    public function setParam($key, $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    public function get($key)
    {
        return $this->params[$key] ?? null;
    }

    public function all()
    {
        return $this->params;
    }

    public function clean()
    {
        return array_filter($this->all(), function ($value) {
            return !empty($value);
        });
    }

    /**
     * @throws QueryParamRequiredException
     * @return string
     */
    public function getQuery()
    {
        $this->throwValidate();

        return http_build_query($this->params);
    }

    public function forgetAttribute($key)
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
