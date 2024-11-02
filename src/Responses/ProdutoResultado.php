<?php

namespace Vluzrmos\Precodahora\Responses;

use Vluzrmos\Precodahora\Models\Estabelecimento;
use Vluzrmos\Precodahora\Models\Produto;

class ProdutoResultado
{
    public readonly Produto $produto;
    public readonly Estabelecimento $estabelecimento;

    public function __construct(array $data = [])
    {
        $this->produto = new Produto($data['produto'] ?? []);
        $this->estabelecimento = new Estabelecimento($data['estabelecimento'] ?? []);
    }
}
