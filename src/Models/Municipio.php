<?php

namespace Vluzrmos\Precodahora\Models;

/**
 * @property string $codigoIBGE
 * @property string $localidade
 * @property float $latitude
 * @property float $longitude
 */
class Municipio extends BaseModel
{
    protected string $keyName = 'codigoIBGE';
}
