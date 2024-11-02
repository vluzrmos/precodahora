<?php

namespace Vluzrmos\Precodahora\Queries;

use Vluzrmos\Precodahora\Exceptions\QueryParamRequiredException;

class ProdutoQuery extends Query
{
    protected array $params = [
        'horas' => 72,
        'raio' => 15,
        'pagina' => 1,
        'ordenar' => 'preco.asc'
    ];

    protected array $required = [
        'latitude',
        'longitude',
    ];

    public function setTermo($termo)
    {
        $this->setParam('termo', $termo);

        return $this;
    }

    public function setGtin($gtin)
    {
        $this->setParam('gtin', $gtin);

        return $this;
    }

    public function setCnpj($cnpj)
    {
        $this->setParam('cnpj', $cnpj);

        return $this;
    }

    public function setHoras($horas)
    {
        $this->setParam('horas', $horas);

        return $this;
    }

    public function setAnp($anp)
    {
        $this->setParam('anp', $anp);

        return $this;
    }

    public function setCodmun($codmun)
    {
        $this->setParam('codmun', $codmun);

        return $this;
    }

    public function setLatitude($latitude)
    {
        $this->setParam('latitude', $latitude);

        return $this;
    }

    public function setLongitude($longitude)
    {
        $this->setParam('longitude', $longitude);

        return $this;
    }

    public function setRaio($raio)
    {
        $this->setParam('raio', $raio);

        return $this;
    }

    public function setPrecomax($precomax)
    {
        $this->setParam('precomax', $precomax);

        return $this;
    }

    public function setPrecomin($precomin)
    {
        $this->setParam('precomin', $precomin);

        return $this;
    }

    public function setPagina($pagina)
    {
        $this->setParam('pagina', $pagina);

        return $this;
    }

    public function setOrdenar($ordenar)
    {
        $this->setParam('ordenar', $ordenar);

        return $this;
    }

    public function setCategorias($categorias)
    {
        $this->setParam('categorias', $categorias);

        return $this;
    }

    public function setProcesso($processo)
    {
        $this->setParam('processo', $processo);

        return $this;
    }

    public function setTotalCategorias($totalCategorias)
    {
        $this->setParam('totalCategorias', $totalCategorias);

        return $this;
    }

    public function setTotalRegistros($totalRegistros)
    {
        $this->setParam('totalRegistros', $totalRegistros);

        return $this;
    }

    public function setTotalPaginas($totalPaginas)
    {
        $this->setParam('totalPaginas', $totalPaginas);

        return $this;
    }

    public function setPageview($pageview)
    {
        $this->setParam('pageview', $pageview);

        return $this;
    }
}
