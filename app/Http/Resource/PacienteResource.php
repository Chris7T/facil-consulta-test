<?php

namespace App\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class PacienteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'telefone' => $this->telefone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}