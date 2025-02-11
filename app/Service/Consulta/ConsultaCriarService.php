<?php

namespace App\Service\Consulta;

use App\Exception\Medico\MedicoNaoEncontradoException;
use App\Exception\Paciente\PacienteNaoEncontradoException;
use App\Models\Consulta;
use App\Repository\ConsultaRepository;
use App\Repository\MedicoRepository;
use App\Repository\PacienteRepository;

class ConsultaCriarService
{
    public function __construct(
        private readonly ConsultaRepository $consultaRepository,
        private readonly PacienteRepository $pacienteRepository,
        private readonly MedicoRepository $medicoRepository,
    ) {}

    public function executar(array $dados): Consulta
    {
        $paciente = $this->pacienteRepository->buscarPeloId($dados['paciente_id']);
        if (is_null($paciente)) {
            throw new PacienteNaoEncontradoException();
        }

        $medico = $this->medicoRepository->buscarPeloId($dados['medico_id']);
        if (is_null($medico)) {
            throw new MedicoNaoEncontradoException();
        }

        return $this->consultaRepository->criar($dados);
    }
}
