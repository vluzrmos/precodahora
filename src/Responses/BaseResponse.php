<?php

namespace Vluzrmos\Precodahora\Responses;

use Vluzrmos\Precodahora\Models\BaseModel;

/**
 * @property string|null $codigo
 * @property string|null $dataConsulta
 * @property string|null $pagina
 * @property string|null $totalPaginas
 * @property string|null $totalRegistros
 * @property array $resultado
 * @property string|null $registrosporPagina
 * @property string|null $dias
 */
class BaseResponse extends BaseModel
{
    public function getResultadoAttribute($value)
    {
        return $value ?: [];
    }
}
