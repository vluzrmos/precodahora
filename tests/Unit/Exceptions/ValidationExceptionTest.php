<?php

use Vluzrmos\Precodahora\Exceptions\ValidationException;
use Vluzrmos\Precodahora\Models\ErrorBag;

covers(ValidationException::class);

it('should be instance of ValidationException', function () {
    $exception = new ValidationException();

    expect($exception)->toBeInstanceOf(ValidationException::class);
    expect($exception->getMessage())->toBe('Validation Error');
});

it('should be instance of ValidationException with message', function () {
    $exception = new ValidationException('Test');

    expect($exception)->toBeInstanceOf(ValidationException::class);
    expect($exception->getMessage())->toBe('Test');
});

it('should be instance of ValidationException with message and code', function () {
    $exception = new ValidationException('Test', 500);

    expect($exception)->toBeInstanceOf(ValidationException::class);
    expect($exception->getMessage())->toBe('Test');
    expect($exception->getCode())->toBe(500);
});

it('should be instance of ValidationException errors', function () {
    $exception = ValidationException::fromErrors(new ErrorBag([
        'name' => 'Name is required',
    ]));

    $errors = $exception->getErrors();

    expect($exception)->toBeInstanceOf(ValidationException::class);
    expect($errors)->toBeInstanceOf(ErrorBag::class);
    expect($errors->first('name'))->toBe('Name is required');


    $errors = new ErrorBag([
        'email' => 'Email is required',
    ]);

    $instance = $exception->setErrors($errors);
    $theErrors = $exception->getErrors();

    expect($theErrors === $errors)->toBeTrue();
    expect($instance)->toBe($exception);

    expect($errors->first('email'))->toBe('Email is required');

    $exception->setErrors();

    expect($exception->getErrors())->toBeNull();
});

it('should be instance of ValidationException with errors and code', function () {
    $exception = ValidationException::fromErrors(new ErrorBag([
        'name' => 'Name is required',
    ]), 500);

    $errors = $exception->getErrors();

    expect($exception)->toBeInstanceOf(ValidationException::class);
    expect($errors)->toBeInstanceOf(ErrorBag::class);
    expect($errors->first('name'))->toBe('Name is required');
    expect($exception->getCode())->toBe(500);
});

it('should be instance of ValidationException with errors, code and previous', function () {
    $previous = new Exception('Previous');

    $exception = ValidationException::fromErrors(new ErrorBag([
        'name' => 'Name is required',
    ]), 500, $previous);

    $errors = $exception->getErrors();

    expect($exception)->toBeInstanceOf(ValidationException::class);
    expect($errors)->toBeInstanceOf(ErrorBag::class);
    expect($errors->first('name'))->toBe('Name is required');
    expect($exception->getCode())->toBe(500);
    expect($exception->getPrevious())->toBe($previous);
});

