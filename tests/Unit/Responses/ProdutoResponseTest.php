<?php

use Vluzrmos\Precodahora\Models\Categoria;
use Vluzrmos\Precodahora\Responses\ProdutoResponse;
use Vluzrmos\Precodahora\Responses\ProdutoResultado;

it('can create ProdutoResponse', function () {
    $response = new ProdutoResponse([
        'resultado' => [
            [
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
            ],
            new ProdutoResultado([
                'produto' => [
                    'gtin' => '123457',
                    'descricao' => 'Produto 2',
                    'precoUnitario' => 2.5,
                    'precoLiquido' => 2.5,
                    'precoBruto' => 2.5,
                    'unidade' => 'UN',
                    'data' => '2021-01-02',
                    'foto' => 'https://example.com/image.jpg',
                    'ncm' => 123457,
                    'ncmGrupo' => 'Grupo 2',
                    'anp' => '12345679',
                ],
                'estabelecimento' => [
                    'nomeEstabelecimento' => 'Estabelecimento 2',
                    'cnpj' => '12345678901235',
                    'endLogradouro' => 'Rua 2',
                    'endNumero' => '124',
                    'cep' => '12345-679',
                    'bairro' => 'Bairro 2',
                    'municipio' => 'Município 2',
                    'uf' => 'UF',
                    'telefone' => '(11) 1234-5679',
                    'latitude' => -23.5489,
                    'longitude' => -46.6388,
                    'tentativa' => 2,
                    'distancia' => 2.5,
                ]
            ]),
        ],
        'categorias' => [
            [
                'codigo' => '0101',
                'descricao' => 'Categoria 1',
            ],
            new Categoria([
                'codigo' => '0102',
                'descricao' => 'Categoria 2',
            ])
        ]
    ]);

    expect($response)->toBeInstanceOf(ProdutoResponse::class);

    expect($response->resultado)->toHaveCount(2);

    $resultado = $response->resultado[0];

    expect($resultado)->toBeInstanceOf(ProdutoResultado::class);
    expect($resultado->produto)->not()->toBeNull();
    expect($resultado->estabelecimento)->not()->toBeNull();

    expect($resultado->produto->gtin)->toBe('123456');
    expect($resultado->produto->descricao)->toBe('Produto 1');
    expect($resultado->produto->precoUnitario)->toBe(1.5);
});
