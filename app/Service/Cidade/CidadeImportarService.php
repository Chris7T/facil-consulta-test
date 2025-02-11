<?php

namespace App\Service\Cidade;

use App\Client\IBGE\IBGEClient;
use App\Repository\CidadeRepository;

class CidadeImportarService
{
    public function __construct(
        private readonly IBGEClient $ibgeClient,
        private readonly CidadeRepository $cidadeRepository
    ) {}

    public function executar(): array
    {
        $cidades = $this->ibgeClient->listarCidades();
        $tamanhoDoLote = 500;

        $cidadeFormatado = collect($cidades)->map(function ($cidade) {
            return [
                'nome' => $cidade['nome'],
                'estado' => $cidade['microrregiao']['mesorregiao']['UF']['sigla'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        })->chunk($tamanhoDoLote);

        $quantidadeImportado = 0;
        foreach ($cidadeFormatado as $lote) {
            $quantidadeImportado += $this->cidadeRepository->inserirLote($lote->toArray());
        }

        return [
            'total_importado' => $quantidadeImportado
        ];
    }
}
