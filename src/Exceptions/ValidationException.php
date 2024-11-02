<?php

namespace Vluzrmos\Precodahora\Exceptions;

use RuntimeException;
use Vluzrmos\Precodahora\Models\ErrorBag;

class ValidationException extends RuntimeException
{
    protected ?ErrorBag $errors = null;

    public function __construct(string $message = "Validation Error", int $code = 422, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromErrors(ErrorBag $errors, $code = 422, $previous = null)
    {
        $instance = new static("Validation Error", $code, $previous);

        $instance->errors = $errors;

        return $instance;
    }

    public function getErrors(): ?ErrorBag
    {
        return $this->errors;
    }

    public function setErrors(?ErrorBag $errors = null)
    {
        $this->errors = $errors;

        return $this;
    }
}
