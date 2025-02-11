<?php

namespace App\Http\Requests\Consulta;

use Illuminate\Foundation\Http\FormRequest;

class ConsultaCriarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'medico_id' => ['nullable', 'int'],
            'paciente_id' => ['nullable', 'int'],
            'data' => ['nullable', 'date', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
