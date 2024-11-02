<?php

require __DIR__ . '/vendor/autoload.php';

use Vluzrmos\Precodahora\Client;
use Vluzrmos\Precodahora\Exceptions\ValidationException;
use Vluzrmos\Precodahora\Queries\ProdutoQuery;

$client = new Client();

try {
    $response = $client->produto(new ProdutoQuery([
        'termo' => 'feijao fradinho',
        'latitude' => -14.7854636,
        'longitude' => -39.2731087,
    ]));
} catch (ValidationException $e) {
    $errors = $e->getErrors();

    echo implode("\n", $errors?->all()) . PHP_EOL;

    exit(1);
}


foreach ($response->resultado ?? [] as $resultado) {
    echo "Produto: {$resultado->produto->descricao}\n";
    echo "Preço: {$resultado->produto->precoUnitario}\n";
    echo "Data: {$resultado->produto->data}\n";
    echo "Estabelecimento: {$resultado->estabelecimento->nomeEstabelecimento}\n";
    echo "Endereço: {$resultado->estabelecimento->endLogradouro}, nº{$resultado->estabelecimento->endNumero}\n";
    echo "Bairro: {$resultado->estabelecimento->bairro}\n";
    echo "CEP: {$resultado->estabelecimento->cep}\n";
    echo "Cidade: {$resultado->estabelecimento->municipio}\n";
    echo "Estado: {$resultado->estabelecimento->uf}\n";
    echo "Telefone: {$resultado->estabelecimento->telefone}\n";

    echo "--------------------------------\n";
}
