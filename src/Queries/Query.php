<?php

namespace Vluzrmos\Precodahora\Queries;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validation;
use Vluzrmos\Precodahora\Exceptions\ValidationException;
use Vluzrmos\Precodahora\Models\ErrorBag;

class Query
{
    protected array $params = [];

    /**
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->params = array_merge($this->params, $params);
    }

    public function getValidationRules()
    {
        return [];
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
        $validator = Validation::createValidator();
        $errors = new ErrorBag();

        $violations = $validator->validate(
            $this->params,
            new Collection(
                $this->getValidationRules(),
                allowExtraFields: true,
            ),
        );

        if ($violations->count() > 0) {
            foreach ($violations as $violation) {
                $key = str_replace(['][', '[', ']'], ['.', '', ''], $violation->getPropertyPath());

                $errors->add($key, $violation->getMessage());
            }
        }

        if ($throw && !$errors->isEmpty()) {
            throw ValidationException::fromErrors($errors);
        }

        return true;
    }

    public function set(string $key, mixed $value)
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

    public function __call($name, array $arguments)
    {
        if (mb_stripos($name, 'set') === 0) {
            $name = lcfirst(substr($name, 3));

            $this->set($name, $arguments[0]);

            return $this;
        }

        if (mb_stripos($name, 'get') === 0) {
            $name = lcfirst(substr($name, 3));

            return $this->get($name);
        }

        if (empty($arguments)) {
            return $this->get($name);
        }

        $this->set($name, $arguments[0]);

        return $this;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
}
