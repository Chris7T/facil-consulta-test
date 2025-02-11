<?php

namespace App\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'especialidade' => $this->especialidade,
            'cidade_id' => $this->cidade_id,
            'cidade_nome' => $this->cidade_nome,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}