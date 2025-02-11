<?php

namespace App\Service\Paciente;

use App\Exception\Paciente\PacienteNaoEncontradoException;
use App\Models\Paciente;
use App\Repository\PacienteRepository;

class PacienteAtualizarService
{
    public function __construct(
        private readonly PacienteRepository $pacienteRepository
    ) {}

    public function executar(array $dados, int $pacienteId): Paciente
    {
        $paciente = $this->pacienteRepository->buscarPeloId($pacienteId);

        if (!$paciente) {
            throw new PacienteNaoEncontradoException();
        }

        return $this->pacienteRepository->atualizar($pacienteId, $dados);
    }
}