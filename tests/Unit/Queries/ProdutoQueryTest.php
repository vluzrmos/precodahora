<?php

use Vluzrmos\Precodahora\Exceptions\QueryParamRequiredException;
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

it('should ordenar', function ($by, $throw) {
    $query = new ProdutoQuery();

    if ($throw) {
        $this->expectException(QueryParamRequiredException::class);
    }

    $query->ordenar($by);

    expect($query->ordenar)->toBe($by);
})->with([
    ['preco.desc', false],
    ['preco.asc', false],
    ['distancia.desc', false],
    ['distancia.asc', false],
    ['data.desc', false],
    ['data.asc', false],
    ['something.else', true],
]);

it('should ordenar helpers', function ($by, $asc, $expected) {
    $query = new ProdutoQuery();
    $query->latitude(1.0);
    $query->longitude(1.0);
    $query->termo('termo');

    $query->{$by}($asc);

    expect($query->ordenar)->toBe($expected);
    expect($query->validate())->toBeTrue();
})->with([
    ['ordenarPorPreco', true, 'preco.asc'],
    ['ordenarPorPreco', false, 'preco.desc'],
    ['ordenarPorDistancia', true, 'distancia.asc'],
    ['ordenarPorDistancia', false, 'distancia.desc'],
    ['ordenarPorData', true, 'data.asc'],
    ['ordenarPorData', false, 'data.desc'],
]);

it('should get ordenar', function () {
    $query = new ProdutoQuery();
    $query->ordenar('preco.desc');
    expect($query->ordenar)->toBe('preco.desc');
    expect($query->ordenar())->toBe('preco.desc');
});
