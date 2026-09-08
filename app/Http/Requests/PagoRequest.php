<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lecturas_id'  => 'required|exists:tb_lecturas,id|unique:tb_pagos,lecturas_id',
            'monto_pago'   => 'required|numeric|min:0',
            'fecha_pago'   => 'required|date',
            'metodo_pago'  => 'required|in:Efectivo,Credito,Debito',
        ];
    }

    public function messages(): array
    {
        return [
            'lecturas_id.required' => 'Debes seleccionar una lectura.',
            'lecturas_id.exists'   => 'La lectura seleccionada no existe.',
            'lecturas_id.unique'   => 'Esta lectura ya tiene un pago registrado.',
            'monto_pago.required'  => 'Debes ingresar el monto del pago.',
            'monto_pago.numeric'   => 'El monto debe ser un número.',
            'monto_pago.min'       => 'El monto no puede ser negativo.',
            'fecha_pago.required'  => 'Debes ingresar la fecha del pago.',
            'fecha_pago.date'      => 'La fecha debe ser una fecha válida.',
            'metodo_pago.required' => 'Debes seleccionar un método de pago.',
            'metodo_pago.in'       => 'El método de pago debe ser Efectivo, Crédito o Débito.',
        ];
    }
}
