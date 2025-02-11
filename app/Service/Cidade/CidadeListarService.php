<?php

namespace App\Service\Cidade;

use App\Client\IBGE\IBGEClient;
use App\Repository\CidadeRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CidadeListarService
{
    public function __construct(
        private readonly IBGEClient $ibgeClient,
        private readonly CidadeRepository $cidadeRepository
    ) {}

    public function executar(?string $nome = null): LengthAwarePaginator
    {
        return $this->cidadeRepository->listar($nome);
    }
}
