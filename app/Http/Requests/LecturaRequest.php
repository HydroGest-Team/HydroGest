<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LecturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contador_id'    => 'required|exists:tb_contadores,id',
            'periodo_id'     => 'required|exists:tb_periodos,id',
            'lectura_actual' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'contador_id.required' => 'Debes seleccionar un contador.',
            'contador_id.exists'   => 'El contador seleccionado no existe.',
            'periodo_id.required'  => 'No hay un período seleccionado.',
            'periodo_id.exists'    => 'El período seleccionado no existe.',
            'lectura_actual.required' => 'Debes ingresar la lectura actual.',
            'lectura_actual.numeric'  => 'La lectura actual debe ser un número.',
            'lectura_actual.min'      => 'La lectura actual no puede ser negativa.',
        ];
    }
}