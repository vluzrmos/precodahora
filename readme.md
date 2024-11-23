# Preço da Hora / Bahia

## Descrição

Cliente para busca de preços dos produtos nos principais mercados/supermercados da Bahia.

Os preços são obtidos através do site [https://precodahora.ba.gov.br/](https://precodahora.ba.gov.br/).

## Como usar

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Vluzrmos\Precodahora\Client;
use Vluzrmos\Precodahora\Exceptions\ValidationException;
use Vluzrmos\Precodahora\Queries\ProdutoQuery;

$client = new Client();

// Código IBGE da Cidade de Itabuna/Bahia
$codigoIBGEItabuna = 2914802;

try {
    // Nota: não é necessário pesquisar o município se você já souber a latitude e longitude de um ponto ou do centro do município.
    $municipio = $client->municipios()->findByCodigoIBGE($codigoIBGEItabuna);

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
```

## Docker

```bash
/path/to/this-project/run.sh
```
> Se nenhum argumento for passado, o script fara o build do container docker e executar o comando "php example.php" presente na pasta do projeto.

> Talvez seja necessário dar permissão de execução ao script:

```bash
chmod +x /path/to/this-project/run.sh
```

## Atenção

> **Nota:** Este cliente não é oficial e não tem nenhuma relação com o site [https://precodahora.ba.gov.br/](https://precodahora.ba.gov.br/).


> **Nota²:** O site [https://precodahora.ba.gov.br/](https://precodahora.ba.gov.br/) não possui uma API pública, então este cliente faz web scraping para obter os dados. Use com moderação, pois pode haver bloqueio de acesso ao site.
