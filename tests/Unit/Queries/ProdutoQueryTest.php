<?php

use Vluzrmos\Precodahora\Exceptions\ValidationException;
use Vluzrmos\Precodahora\Models\ErrorBag;
use Vluzrmos\Precodahora\Queries\ProdutoQuery;
use Vluzrmos\Precodahora\Queries\Query;

it('can create ProdutoQuery', function () {
    $query = new ProdutoQuery();
    expect($query)->toBeInstanceOf(ProdutoQuery::class);
    expect($query)->toBeInstanceOf(Query::class);
});

it('can add parameters to ProdutoQuery', function () {
    $query = new ProdutoQuery();
    $query->set('name', 'John Doe');
    $query->set('age', 30);
    $query->set('email', 'something@like.this');
    $query->is_admin = true;
    $query->ordenarPorPreco();

    expect($query->name)->toBe('John Doe');
    expect($query->age)->toBe(30);
    expect($query->email)->toBe('something@like.this');
    expect($query->is_admin)->toBeTrue();
    expect($query->ordenar)->toBe('preco.asc');
});

it('throw validation exception for latitude and longitude', function () {
    $query = new ProdutoQuery();

    $exception = null;
    $errors = new ErrorBag();

    try {
        $query->throwValidate();
    } catch (ValidationException $exception) {
        $errors = $exception->getErrors();
    }

    expect($exception)->toBeInstanceOf(ValidationException::class);
    
    expect($errors->has('latitude'))->toBeTrue();
    expect($errors->has('longitude'))->toBeTrue();
    expect(count($errors->messages()))->toBe(2);
    expect(count($errors->all()))->toBe(2);
});
