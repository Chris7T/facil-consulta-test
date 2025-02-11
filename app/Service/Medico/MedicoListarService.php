<?php

namespace App\Service\Medico;

use App\Repository\MedicoRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class MedicoListarService
{
    public function __construct(
        private readonly MedicoRepository $medicoRepository,
    ) {}

    public function executar(?string $nome = null): LengthAwarePaginator
    {
        return $this->medicoRepository->listar($nome);
    }
}
