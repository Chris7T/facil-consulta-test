<?php

namespace App\Repository;

use App\Models\Medico;
use Illuminate\Pagination\LengthAwarePaginator;

class MedicoRepository
{
    public function criar(array $dados): void
    {
        Medico::create($dados);
    }

    public function listarPorCidade(int $cidadeId, ?string $nome = null): LengthAwarePaginator
    {
        return Medico::select(['medicos.*', 'cidades.nome as cidade_nome'])
            ->leftjoin('cidades', 'medicos.cidade_id', '=', 'cidades.id')->where('cidade_id', $cidadeId)
            ->when(
                !is_null($nome),
                fn($query) => $query->whereRaw('LOWER(medicos.nome) LIKE ?', ['%' . strtolower($nome) . '%'])
            )->orderBy('nome')->paginate(20);
    }

    public function listar(?string $nome = null): LengthAwarePaginator
    {
        return Medico::select(['medicos.*', 'cidades.nome as cidade_nome'])
            ->leftjoin('cidades', 'medicos.cidade_id', '=', 'cidades.id')
            ->when(
                !is_null($nome),
                fn($query) => $query->whereRaw('LOWER(medicos.nome) LIKE ?', ['%' . strtolower($nome) . '%'])
            )
            ->orderBy('nome')->paginate(20);
    }

    public function buscarPeloId(int $id): ?Medico
    {
        return Medico::find($id);
    }
}
