<?php

namespace App\Http\Requests\Cidade;

use Illuminate\Foundation\Http\FormRequest;

class CidadeListarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'nome' => ['nullable', 'string', 'max:100'],
        ];
    }
}
