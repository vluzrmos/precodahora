<?php

use Vluzrmos\Precodahora\Models\Municipio;
use Vluzrmos\Precodahora\Responses\MunicipioResponse;

it('can create MunicipioResponse', function () {
    $response = new MunicipioResponse([
        'resultado' => [
            [
                'codigoIBGE' => 123456,
                'localidade' => 'São Paulo',
                'latitude' => -23.5489,
                'longitude' => -46.6388,
            ],
            [
                'codigoIBGE' => 654321,
                'localidade' => 'Rio de Janeiro',
                'latitude' => -22.9068,
                'longitude' => -43.1729,
            ],
            new Municipio([
                'codigoIBGE' => 987654,
                'localidade' => 'Brasília',
                'latitude' => -15.793889,
                'longitude' => -47.882778,
            ]),
        ],
    ]);

    expect($response)->toBeInstanceOf(MunicipioResponse::class);

    expect($response->resultado)->toHaveCount(3);

    $municipio = $response->findByCodigoIBGE(123456);

    expect($municipio)->not()->toBeNull();
    expect($municipio->codigoIBGE)->toBe(123456);
    expect($municipio->localidade)->toBe('São Paulo');

    $municipios = $response->findByLocalidade('São Paulo');

    expect($municipios)->toHaveCount(1);
    expect($municipios[0]->codigoIBGE)->toBe(123456);
    expect($municipios[0]->localidade)->toBe('São Paulo');
});