<?php

namespace Vluzrmos\Precodahora\Responses;

use Vluzrmos\Precodahora\Models\Municipio;
use function Symfony\Component\String\u;

/**
 * @property Municipio[]|null $resultado
 */
class MunicipioResponse extends BaseResponse
{
    public function setResultadoAttribute($value)
    {
        $this->attributes['resultado'] = array_map(function ($item) {
            if ($item instanceof Municipio) {
                return $item;
            }

            return new Municipio($item);
        }, $value);
    }

    public function findByCodigoIBGE(int $codigoIBGE): ?Municipio
    {
        foreach ($this->resultado ?? [] as $municipio) {
            if ($municipio->codigoIBGE === $codigoIBGE) {
                return $municipio;
            }
        }
    }

    /**
     * @return Municipio[]
     */
    public function findByLocalidade(string $localidade): array
    {
        return array_values(
            array_filter($this->resultado, function ($municipio) use ($localidade) {
                return u($municipio->localidade)->ascii()
                    ->ignoreCase()
                    ->indexOf(
                        u($localidade)->ascii()
                    ) !== null;
            })
        );
    }
}
