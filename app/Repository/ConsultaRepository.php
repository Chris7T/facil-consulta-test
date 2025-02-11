<?php

namespace App\Repository;

use App\Models\Consulta;

class ConsultaRepository
{
    public function criar(array $dados): Consulta
    {
        return Consulta::create($dados)->load(['paciente', 'medico']);
    }
}
