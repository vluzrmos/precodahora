<?php

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Vluzrmos\Precodahora\Exceptions\ValidationException;
use Vluzrmos\Precodahora\Queries\Query;

covers(Query::class);

it('can create Query', function () {
    $query = new Query();
    expect($query)->toBeInstanceOf(Query::class);
});

it('can add parameters to Query', function () {
    $query = new Query();
    $query->set('name', 'John Doe');
    $query->set('age', 30);
    $query->set('email', 'something@like.this');
    $query->is_admin = true;
    $query->something('else');

    expect($query->name)->toBe('John Doe');
    expect($query->name())->toBe('John Doe');
    expect($query->age)->toBe(30);
    expect($query->email)->toBe('something@like.this');
    expect($query->is_admin)->toBeTrue();
    expect($query->something())->toBe('else');

    $data = [
        'name' => 'John Doe',
        'age' => 30,
        'email' => 'something@like.this',
        'is_admin' => true,
        'something' => 'else',
    ];

    expect($query->all())->toBe($data);

    expect($query->toString())->toBe(http_build_query($data));

    $query->empty_value = '';

    $data = $query->clean();

    expect($data)->not()->toHaveKey('empty_value');

    $query->forgetit = 1;

    $query->forgetAttribute('forgetit');

    expect($query->all())->not()->toHaveKey('forgetit');

    $query->setValue(1);

    expect($query->getValue())->toBe(1);
    expect($query->value())->toBe(1);

    $instance = new class extends Query {
        public function getValidationRules()
        {
            return [
                'value' => new Optional([
                    new NotBlank(message: 'value is required')
                ])
            ];
        }
    };

    $instance->setValue('');

    /** @var ValidationException $exception */
    $exception = null;

    try {
        $instance->throwValidate();
    } catch (ValidationException $e) {
        $exception = $e;
    }

    expect($exception->getErrors()->messages())->toHaveKey('value');
    expect($exception->getErrors()->first('value'))->toBe('value is required');
});
