<?php

namespace App\Service\Medico;
;

use App\Exception\Cidade\CidadeNaoEncontradaException;
use App\Repository\CidadeRepository;
use App\Repository\MedicoRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class MedicoListarPorCidadeService
{
    public function __construct(
        private readonly MedicoRepository $medicoRepository,
        private readonly CidadeRepository $cidadeRepository
    ) {}

    public function executar(int $cidadeId, ?string $nome = null): LengthAwarePaginator
    {
        $cidade = $this->cidadeRepository->buscarPeloId($cidadeId);
        if(is_null($cidade)){
            throw new CidadeNaoEncontradaException();
        }

        return $this->medicoRepository->listarPorCidade($cidadeId, $nome);
    }
}
