<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class MedicoCriarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'especialidade' => ['required', 'string', 'max:100'],
            'cidade_id' => ['required', 'integer']
        ];
    }
}
