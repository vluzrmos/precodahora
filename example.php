<?php

require __DIR__ . '/vendor/autoload.php';

use Vluzrmos\Precodahora\Client;
use Vluzrmos\Precodahora\Exceptions\ValidationException;
use Vluzrmos\Precodahora\Queries\ProdutoQuery;

$client = new Client();

$codigoIBGEItabuna = 2914802;
$codigoIBGE = (int) ($argv[1] ?? $codigoIBGEItabuna);

try {
    $municipio = $client->municipios()->findByCodigoIBGE($codigoIBGE);

    $response = $client->produto(new ProdutoQuery([
        'termo' => 'feijao fradinho',
        'latitude' => $municipio?->latitude,
        'longitude' => $municipio?->longitude,
    ]));
} catch (ValidationException $e) {
    $errors = $e->getErrors();

    echo implode("\n", $errors?->all()) . PHP_EOL;

    exit(1);
}

echo "Município: {$municipio->localidade}\n";
echo "Latitude: {$municipio->latitude}\n";
echo "Longitude: {$municipio->longitude}\n";
echo "--------------------------------\n\n";

foreach ($response->resultado ?? [] as $resultado) {
    $produto = $resultado->produto;
    $estabelecimento = $resultado->estabelecimento;

    echo "Produto: {$produto->descricao}\n";
    echo "Preço: {$produto->precoUnitario}\n";
    echo "Data: {$produto->data}\n";
    echo "Estabelecimento: {$estabelecimento->nomeEstabelecimento}\n";
    echo "Endereço: {$estabelecimento->endLogradouro}, nº{$estabelecimento->endNumero}\n";
    echo "Bairro: {$estabelecimento->bairro}\n";
    echo "CEP: {$estabelecimento->cep}\n";
    echo "Cidade: {$estabelecimento->municipio}\n";
    echo "Estado: {$estabelecimento->uf}\n";
    echo "Telefone: {$estabelecimento->telefone}\n";

    echo "--------------------------------\n";
}
