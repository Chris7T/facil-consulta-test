<?php

namespace App\Repository;

use App\Models\Paciente;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class PacienteRepository
{
    public function buscarPeloId(int $id): ?Paciente
    {
        return Paciente::find($id);
    }

    public function listarPeloMedico(int $medicoId, ?Carbon $posteriorData = null, ?string $nome = null): LengthAwarePaginator
    {
        return Paciente::select(['pacientes.*', 'consultas.data', 'consultas.id as consulta_id'])
            ->join('consultas', 'pacientes.id', '=', 'consultas.paciente_id')
            ->where('consultas.medico_id', $medicoId)
            ->when(!is_null($posteriorData), fn($query) => $query->where('consultas.data', '>', $posteriorData))
            ->when(!is_null($nome), fn($query) => $query->whereRaw('LOWER(pacientes.nome) LIKE ?', ['%' . strtolower($nome) . '%']))
            ->orderBy('consultas.data')
            ->distinct()
            ->paginate(15);
    }

    public function criar(array $dados): Paciente
    {
        return Paciente::create($dados);
    }

    public function existeCpf(string $cpf): bool
    {
        return Paciente::where('cpf', $cpf)->exists();
    }

    public function atualizar(int $pacienteId, array $dados): Paciente
    {
        $paciente = Paciente::find($pacienteId);
        $paciente->update($dados);

        return $paciente->refresh();
    }
}
