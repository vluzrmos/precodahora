<?php

namespace Vluzrmos\Precodahora\Models;

/**
 * @property string gtin
 * @property string descricao
 * @property float precoUnitario
 * @property float precoLiquido
 * @property float precoBruto
 * @property string unidade
 * @property string $data
 * @property string $foto
 * @property int $ncm
 * @property string $ncmGrupo
 * @property string $anp
 * 
 */
class Produto extends BaseModel
{
    protected string $keyName = 'gtin';
}
