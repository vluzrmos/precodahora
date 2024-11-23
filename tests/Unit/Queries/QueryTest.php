<?php

use Vluzrmos\Precodahora\Queries\Query;

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
});
