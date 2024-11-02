<?php

namespace Vluzrmos\Precodahora\Responses;

use Vluzrmos\Precodahora\Models\Categoria;

class ProdutoResponse
{
    public readonly ?string $codigo;
    public readonly ?string $dataConsulta;
    public readonly ?string $pagina;
    public readonly ?string $totalPaginas;
    public readonly ?string $totalRegistros;
    public readonly ?string $registrosporPagina;
    public readonly ?string $dias;
    protected array $categorias = [];

    /**
     * @var ProdutoResultado[]
     */
    protected array $resultado = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            if ($key === 'categorias') {
                $this->categorias = array_map(function ($item) {
                    return new Categoria($item);
                }, $value);

                continue;
            }

            if ($key === 'resultado') {
                $this->resultado = array_map(function ($item) {
                    return new ProdutoResultado($item);
                }, $value);

                continue;
            }

            $this->{$key} = $value;
        }
    }

    public function getResultado()
    {
        return $this->resultado;
    }

    public function getCategorias()
    {
        return $this->categorias;
    }
}
