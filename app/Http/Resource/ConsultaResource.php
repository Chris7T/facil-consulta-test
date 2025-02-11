<?php

namespace App\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsultaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'data' => $this->data,
            'medico_id' => $this->medico->id,
            'medico_nome' => $this->medico->nome,
            'paciente_id' => $this->paciente->id,
            'paciente_nome' => $this->paciente->nome,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}