<?php

namespace Vluzrmos\Precodahora\Responses;

use ArrayAccess;
use JsonSerializable;
use Vluzrmos\Precodahora\Models\BaseModel;
use Vluzrmos\Precodahora\Models\Categoria;

/**
 * @property string|null $codigo
 * @property string|null $dataConsulta
 * @property string|null $pagina
 * @property string|null $totalPaginas
 * @property string|null $totalRegistros
 * @property string|null $registrosporPagina
 * @property string|null $dias
 * @property Categoria[]|null $categorias
 * @property ProdutoResultado[]|null $resultado
 */
class ProdutoResponse extends BaseModel
{
    public function setResultadoAttribute($value)
    {
        $this->attributes['resultado'] = array_map(function ($item) {
            if ($item instanceof ProdutoResultado) {
                return $item;
            }

            return new ProdutoResultado($item);
        }, $value);
    }

    public function setCategoriasAttribute($value)
    {
        $this->attributes['categorias'] = array_map(function ($item) {
            if ($item instanceof Categoria) {
                return $item;
            }

            return new Categoria($item);
        }, $value);
    }
}
