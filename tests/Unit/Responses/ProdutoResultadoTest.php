<?php

use Vluzrmos\Precodahora\Responses\ProdutoResultado;

covers(ProdutoResultado::class);

it('can instantiate ProdutoResultado', function () {
    $resultado = new ProdutoResultado([
        'produto' => [
            'gtin' => '123456',
            'descricao' => 'Produto 1',
            'precoUnitario' => 1.5,
            'precoLiquido' => 1.5,
            'precoBruto' => 1.5,
            'unidade' => 'UN',
            'data' => '2021-01-01',
            'foto' => 'https://example.com/image.jpg',
            'ncm' => 123456,
            'ncmGrupo' => 'Grupo 1',
            'anp' => '12345678',
        ],
        'estabelecimento' => [
            'nomeEstabelecimento' => 'Estabelecimento 1',
            'cnpj' => '12345678901234',
            'endLogradouro' => 'Rua 1',
            'endNumero' => '123',
            'cep' => '12345-678',
            'bairro' => 'Bairro 1',
            'municipio' => 'Município 1',
            'uf' => 'UF',
            'telefone' => '(11) 1234-5678',
            'latitude' => -23.5489,
            'longitude' => -46.6388,
            'tentativa' => 1,
            'distancia' => 1.5,
        ]
    ]);

    expect($resultado)->toBeInstanceOf(ProdutoResultado::class);

    expect($resultado->produto)->toBeInstanceOf(\Vluzrmos\Precodahora\Models\Produto::class);
    expect($resultado->produto->gtin)->toBe('123456');
    expect($resultado->produto->descricao)->toBe('Produto 1');
    expect($resultado->produto->precoUnitario)->toBe(1.5);
    expect($resultado->produto->precoLiquido)->toBe(1.5);
    expect($resultado->produto->precoBruto)->toBe(1.5);
    expect($resultado->produto->unidade)->toBe('UN');
    expect($resultado->produto->data)->toBe('2021-01-01');
    expect($resultado->produto->foto)->toBe('https://example.com/image.jpg');
    expect($resultado->produto->ncm)->toBe(123456);
    expect($resultado->produto->ncmGrupo)->toBe('Grupo 1');
    expect($resultado->produto->anp)->toBe('12345678');

    expect($resultado->estabelecimento)->toBeInstanceOf(\Vluzrmos\Precodahora\Models\Estabelecimento::class);
    expect($resultado->estabelecimento->nomeEstabelecimento)->toBe('Estabelecimento 1');
    expect($resultado->estabelecimento->cnpj)->toBe('12345678901234');
    expect($resultado->estabelecimento->endLogradouro)->toBe('Rua 1');
    expect($resultado->estabelecimento->endNumero)->toBe('123');
    expect($resultado->estabelecimento->cep)->toBe('12345-678');
    expect($resultado->estabelecimento->bairro)->toBe('Bairro 1');
    expect($resultado->estabelecimento->municipio)->toBe('Município 1');
    expect($resultado->estabelecimento->uf)->toBe('UF');
    expect($resultado->estabelecimento->telefone)->toBe('(11) 1234-5678');
    expect($resultado->estabelecimento->latitude)->toBe(-23.5489);
    expect($resultado->estabelecimento->longitude)->toBe(-46.6388);
    expect($resultado->estabelecimento->tentativa)->toBe(1);
    expect($resultado->estabelecimento->distancia)->toBe(1.5);
});