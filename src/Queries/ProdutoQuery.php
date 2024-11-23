<?php

namespace Vluzrmos\Precodahora\Queries;

use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Vluzrmos\Precodahora\Exceptions\QueryParamRequiredException;

/**
 * Class ProdutoQuery
 * @package Vluzrmos\Precodahora\Queries
 * 
 * @property int $horas
 * @method self|null|mixed horas(int $horas = null)     Define a quantidade de horas para buscar os resultados
 * 
 * @property int $raio
 * @method self|null|mixed raio(int $raio = null)      Define o raio de busca em KM
 * 
 * @property int $pagina
 * @method self|null|mixed pagina(int $pagina = null)   Define a página da busca
 * 
 * @property string $ordenar
 * @method self|null|mixed ordenar(string $ordenar = null) Define a ordenação dos resultados, exemplo: preco.asc
 * 
 * @property string $termo
 * @method self|null|mixed termo(string $termo = null)  Busca por um termo, exemplo: FEIJAO
 * 
 * @property string $gtin
 * @method self|null|mixed gtin(string $gtin = null)    Código de Barras (Global Trade Item Number), define a busca por um código de barras
 * 
 * @property string $cnpj
 * @method self|null|mixed cnpj(string $cnpj = null)   CNPJ do estabelecimento, define a busca por um CNPJ
 * 
 * @property string $latitude
 * @method self|null|mixed latitude(string $latitude = null)    Define a latitude da cidade para busca
 * 
 * @property string $longitude
 * @method self|null|mixed longitude(string $longitude = null) Define a longitude da cidade para busca
 * 
 * @property string $anp
 * @method self|null|mixed anp(string $anp = null)  Agência Nacional do Petróleo... Define a busca por um tipo de combustível, exemplo: GASOLINA
 * 
 * @property string $precomax
 * @method self|null|mixed precomax(float $precomax = null) Define o preço máximo para busca
 * 
 * @property string $precomin
 * @method self|null|mixed precomin(float $precomin = null) Define o preço mínimo para busca
 * 
 * @property string $categorias
 * @method self|null|mixed categorias(string $categorias = null) Define as categorias dos produtos separados por vírgula, exemplo: "1,2,3"
 * 
 */
class ProdutoQuery extends Query
{
    protected array $params = [
        'horas' => 72,
        'raio' => 15,
        'pagina' => 1,
        'ordenar' => 'preco.asc'
    ];

    protected $ordenarOptions = [
        'preco.asc',
        'preco.desc',
        'distancia.asc',
        'distancia.desc',
        'data.asc',
        'data.desc',
    ];

    public function getValidationRules()
    {
        return [
            'latitude' => [
                new NotBlank(message: 'ProdutoQuery param latitude is required'),
                new GreaterThanOrEqual(-90, message: 'ProdutoQuery param latitude must be greater than or equal to -90'),
                new LessThanOrEqual(90, message: 'ProdutoQuery param latitude must be less than or equal to 90'),
            ],
            'longitude' => [
                new NotBlank(message: 'ProdutoQuery param longitude is required'),
                new GreaterThanOrEqual(-180, message: 'ProdutoQuery param longitude must be greater than or equal to -180'),
                new LessThanOrEqual(180, message: 'ProdutoQuery param longitude must be less than or equal to 180'),
            ],
            'pagina' => [
                new NotBlank(message: 'ProdutoQuery param pagina is required'),
                new GreaterThanOrEqual(1, message: 'ProdutoQuery param pagina must be greater than or equal to 1'),
            ],
            'horas' => [
                new NotBlank(message: 'ProdutoQuery param horas is required'),
                new GreaterThanOrEqual(12, message: 'ProdutoQuery param horas must be greater than or equal to 12'),
                new LessThanOrEqual(72, message: 'ProdutoQuery param horas must be less than or equal to 72'),
            ],
            'raio' => [
                new NotBlank(message: 'ProdutoQuery param raio is required'),
                new GreaterThanOrEqual(1, message: 'ProdutoQuery param raio must be greater than or equal to 1'),
                new LessThanOrEqual(30, message: 'ProdutoQuery param raio must be less than or equal to 30'),
            ],
            'precomin' => new Optional([
                new GreaterThanOrEqual(0.00, message: 'ProdutoQuery param precomin must be greater than or equal to {{ compared_value }}'),
            ]),
            'precomax' => new Optional([
                new GreaterThanOrEqual(0.00, message: 'ProdutoQuery param precomin must be greater than or equal to {{ compared_value }}'),
            ]),
        ];
    }
    /**
     * @param string $name
     * @param boolean $asc
     * @return self
     */
    public function ordenarPor($name, bool $asc = true)
    {
        return $this->ordenar("{$name}." . ($asc ? 'asc' : 'desc'));
    }

    public function ordenarPorPreco(bool $asc = true)
    {
        return $this->ordenarPor('preco', $asc);
    }

    public function ordenarPorDistancia(bool $asc = true)
    {
        return $this->ordenarPor('distancia', $asc);
    }

    public function ordenarPorData(bool $asc = true)
    {
        return $this->ordenarPor('data', $asc);
    }

    /**
     * @param string|null $ordenar
     * @return self|string
     */
    public function ordenar(?string $ordenar = null)
    {
        if (func_num_args() === 0) {
            return $this->get('ordenar');
        }

        if (!in_array($ordenar, $this->ordenarOptions, true)) {
            throw new QueryParamRequiredException("Query param ordenar is invalid");
        }

        return $this->set('ordenar', $ordenar);
    }
}
