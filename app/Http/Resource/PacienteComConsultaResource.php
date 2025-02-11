<?php

namespace App\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class PacienteComConsultaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'consulta_id' => $this->consulta_id,
            'consulta_data' => $this->data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}