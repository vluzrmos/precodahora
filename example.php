<?php

require __DIR__ . '/vendor/autoload.php';

use Vluzrmos\Precodahora\Client;
use Vluzrmos\Precodahora\Exceptions\ValidationException;
use Vluzrmos\Precodahora\Queries\ProdutoQuery;

use function Laravel\Prompts\error;
use function Laravel\Prompts\text;

$client = new Client();

$localidadeOrCodigoIBGE = $argv[1] ?? null;
$termo = $argv[2] ?? null;

$defaultLocalidade = null;
$defaultTermo = null;

while (true) {
    $localidadeOrCodigoIBGE = $localidadeOrCodigoIBGE ?: text(label: 'Digite a localidade ou código IBGE do município', hint: 'Digite "sair" para encerrar', default: (string) $defaultLocalidade);

    if (strtolower($localidadeOrCodigoIBGE) === 'sair') {
        echo "Encerrando o programa...\n";
        exit(0);
    }

    $termo = $termo ?: text(label: 'Digite o termo de busca ou o código de barras', hint: 'Digite "sair" para encerrar', default: (string) $defaultTermo);

    if (strtolower($termo) === 'sair') {
        echo "Encerrando o programa...\n";
        exit(0);
    }

    if (empty($localidadeOrCodigoIBGE) || empty($termo)) {
        error('Localidade/Código IBGE e termo de busca são obrigatórios. Por favor, tente novamente.');
        $localidadeOrCodigoIBGE = null;
        $termo = null;
        continue;
    }

    try {
        $municipio = is_numeric($localidadeOrCodigoIBGE) ?
            $client->municipios()->findByCodigoIBGE($localidadeOrCodigoIBGE) : ($client->municipios()->findByLocalidade($localidadeOrCodigoIBGE)[0] ?? null);

        $query = (new ProdutoQuery());

        if (is_numeric($termo)) {
            $query->gtin($termo);
        } else {
            $query->termo($termo);
        }

        $query->latitude($municipio?->latitude)
            ->longitude($municipio?->longitude)
            ->ordenarPorDistancia();

        $response = $client->produto($query);
    } catch (ValidationException $e) {
        $errors = $e->getErrors();

        echo implode("\n", $errors?->all()) . PHP_EOL;

        exit(1);
    }

    $defaultLocalidade = $localidadeOrCodigoIBGE;
    $defaultTermo = $termo;

    $localidadeOrCodigoIBGE = null;
    $termo = null;

    echo "Município: {$municipio->localidade}\n";
    echo "Latitude: {$municipio->latitude}\n";
    echo "Longitude: {$municipio->longitude}\n";
    echo "--------------------------------\n\n";

    foreach ($response->resultado ?? [] as $resultado) {
        $produto = $resultado->produto;
        $estabelecimento = $resultado->estabelecimento;

        echo "Produto: {$produto->descricao}\n";
        echo "Preço: {$produto->precoUnitario}\n";

        // Necessário usar um visualizador de imagens pois o *Preço da Hora* faz download 
        // da imagem ao invés de apenas exibir.
        if ($produto->foto) {
            $viewer = 'https://gosoccerboy5.github.io/view-images/#';
            echo "Foto: {$viewer}{$produto->foto}\n";
        }

        echo "Data: {$produto->data}\n";
        echo "Estabelecimento: {$estabelecimento->nomeEstabelecimento}\n";
        echo "Endereço: {$estabelecimento->endLogradouro}, nº {$estabelecimento->endNumero}\n";
        echo "Bairro: {$estabelecimento->bairro}\n";
        echo "CEP: {$estabelecimento->cep}\n";
        echo "Cidade: {$estabelecimento->municipio}\n";
        echo "Estado: {$estabelecimento->uf}\n";
        echo "Telefone: {$estabelecimento->telefone}\n";

        echo "--------------------------------\n";
    }
};
