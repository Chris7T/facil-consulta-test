<?php

namespace App\Service\Paciente;

use App\Repository\PacienteRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class PacienteListarPorMedicoService
{
    public function __construct(private readonly PacienteRepository $pacienteRepository) {}

    public function executar(int $medicoId, bool $apenasAgendadas, ?string $nome = null): LengthAwarePaginator
    {
        $posteriorData = $apenasAgendadas ? Carbon::now() : null;
        
        return $this->pacienteRepository->listarPeloMedico($medicoId, $posteriorData, $nome);
    }
}
