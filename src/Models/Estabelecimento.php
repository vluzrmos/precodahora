<?php

namespace Vluzrmos\Precodahora\Models;

/**
 * @property string $nomeEstabelecimento
 * @property string $cnpj
 * @property string $endLogradouro
 * @property string $endNumero
 * @property string $cep
 * @property string $bairro
 * @property string $municipio
 * @property string $uf
 * @property string $telefone
 * @property string $latitude
 * @property string $longitude
 * @property float $tentativa
 * @property float $distancia
 */
class Estabelecimento extends BaseModel
{
    protected string $keyName = 'cnpj';
}
