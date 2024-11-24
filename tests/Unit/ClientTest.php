<?php

use Vluzrmos\Precodahora\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Vluzrmos\Precodahora\Queries\ProdutoQuery;

it('should return a new instance of Client', function () {
    $client = new Client();
    expect($client)->toBeInstanceOf(Client::class);
});

function csrf_html()
{
    return <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR" >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta id="validate" data-id="ImMxZjA1Yjc0MzE0NDZkZjMwY2EwYjc3YTVlZTM5NjgxOWU1NjcyNjQi.Z0KR6w.8by8Z1ydWV44-CyG8A8Z6HA-Y98" />
    </head>
    <body></body>
</html>
HTML;
}

function csrf_response()
{
    return new Response(200, [], csrf_html());
}

function municipio_response(?array $data = null)
{
    $data = $data ?? [
        'codigoIBGE' => 1,
        'localidade' => 'Teste',
        'latitude' => 0.1,
        'longitude' => 0.2,
    ];

    return new Response(200, [], json_encode(
        json_encode(
            [
                'resultado' => [
                    $data,
                ]
            ]
        )
    ));
}

it('can request municipios', function () {
    $handler = new MockHandler([
        csrf_response(),
        municipio_response(),
    ]);

    $http = new HttpClient(['handler' => $handler]);

    $client = new Client($http);

    expect($client)->toBeInstanceOf(Client::class);

    $response = $client->municipios();

    $municipio = $response->resultado[0];

    expect($municipio->codigoIBGE)->toBe(1);
    expect($municipio->localidade)->toBe('Teste');
});


function produto_response(?array $data = null)
{
    $data = $data ?? [
        'produto' => [
            'gtin' => 1,
            'descricao' => 'Teste',
            'unidade' => 'UN',
            'precoUnitario' => 1.0,
            'precoLiquido' => 1.0,
            'precoBruto' => 1.0,
        ],
        'estabelecimento' => [
            'cnpj' => '12345678901234',
            'nomeEstabelecimento' => 'Nome Estabelecimento Teste',
            'municipio' => 'Teste',
        ],
    ];

    return new Response(
        200,
        [],
        json_encode(
            [
                'resultado' => [
                    $data,
                ]
            ]
        )
    );
}
it('can request produtos', function () {
    $handler = new MockHandler([
        csrf_response(),
        produto_response(),
    ]);

    $http = new HttpClient(['handler' => $handler]);

    $client = new Client($http);

    expect($client)->toBeInstanceOf(Client::class);

    $response = $client->produto(
        (new ProdutoQuery())
            ->termo('TESTE')
            ->latitude(0.1)
            ->longitude(0.2)
    );

    $resultado = $response->resultado[0];

    expect($resultado->produto['gtin'])->toBe(1);
    expect($resultado->produto['descricao'])->toBe('Teste');
    expect($resultado->produto['unidade'])->toBe('UN');
    expect((float) $resultado->produto['precoUnitario'])->toBe(1.0);
    expect((float) $resultado->produto['precoLiquido'])->toBe(1.0);
    expect((float) $resultado->produto['precoBruto'])->toBe(1.0);
    expect($resultado->estabelecimento['cnpj'])->toBe('12345678901234');
    expect($resultado->estabelecimento['nomeEstabelecimento'])->toBe('Nome Estabelecimento Teste');
    expect($resultado->estabelecimento['municipio'])->toBe('Teste');
});
