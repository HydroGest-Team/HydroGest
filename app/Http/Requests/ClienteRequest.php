<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente');

        return [
            'dpi_cliente'          => 'required|digits:13|unique:tb_clientes,dpi_cliente,' . $clienteId,
            'nombre1_cliente'      => 'required|string|max:20',
            'nombre2_cliente'      => 'nullable|string|max:20',
            'nombre3_cliente'      => 'nullable|string|max:20',
            'apellido1_cliente'    => 'required|string|max:20',
            'apellido2_cliente'    => 'nullable|string|max:20',
            'apellido3_cliente'    => 'nullable|string|max:20',
            'telefono_cliente'     => 'nullable|digits:8',
            'direccion_cliente'    => 'nullable|string|max:50',
            'numero_cuenta_cliente'=> 'nullable|string|max:20',
            'activo_cliente'       => 'in:ACTIVO,NO ACTIVO',
        ];
    }

    public function messages(): array
    {
        return [
            'dpi_cliente.required' => 'El DPI es obligatorio.',
            'dpi_cliente.digits'   => 'El DPI debe tener exactamente 13 dígitos.',
            'dpi_cliente.unique'   => 'Este DPI ya está registrado.',
            'nombre1_cliente.required'   => 'El primer nombre es obligatorio.',
            'apellido1_cliente.required' => 'El primer apellido es obligatorio.',
            'telefono_cliente.digits'    => 'El teléfono debe tener 8 dígitos.',
        ];
    }
}