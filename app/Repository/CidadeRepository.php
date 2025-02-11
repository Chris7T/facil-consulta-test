<?php

namespace App\Repository;

use App\Models\Cidade;
use Illuminate\Pagination\LengthAwarePaginator;

class CidadeRepository
{
    public function inserirLote(array $cidades): int
    {
        return Cidade::insertOrIgnore($cidades);
    }

    public function criar(array $cidades): int
    {
        return Cidade::create($cidades);
    }

    public function listar(?string $nome = null): LengthAwarePaginator
    {
        return Cidade::when(
            !is_null($nome),
            fn($query) => $query->whereRaw('LOWER(nome) LIKE ?', ['%' . strtolower($nome) . '%'])
        )->orderBy('nome')->paginate(20);
    }

    public function buscarPeloId(int $id): ?Cidade
    {
        return Cidade::find($id);
    }
}
