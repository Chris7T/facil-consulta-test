<?php

namespace App\Service\Medico;

use App\Repository\MedicoRepository;

class MedicoCriarService
{
    public function __construct(
        private readonly MedicoRepository $medicoRepository,
    ) {}

    public function executar(array $dados): void
    {
        $this->medicoRepository->criar($dados);
    }
}
