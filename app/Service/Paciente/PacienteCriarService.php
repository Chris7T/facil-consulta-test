<?php

namespace App\Service\Paciente;

use App\Exception\Paciente\PacienteCpfInvalidoException;
use App\Repository\PacienteRepository;
use App\Models\Paciente;

class PacienteCriarService
{
    public function __construct(
        private readonly PacienteRepository $pacienteRepository
    ) {}

    public function executar(array $dados): Paciente
    {
        if ($this->pacienteRepository->existeCpf($dados['cpf'])) {
            throw new PacienteCpfInvalidoException();
        }

        return $this->pacienteRepository->criar($dados);
    }
}