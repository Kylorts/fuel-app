<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Order::class);
    }

    public function rules(): array
    {
        return [
            'fuel_type'         => ['required', 'string', 'in:Solar,Solar B30,Solar Industri,Pertamax,Pertalite'],
            'volume_liters'     => ['required', 'numeric', 'gt:0'],
            'unit_price'        => ['required', 'numeric', 'gt:0'],
            'delivery_location' => ['required', 'string', 'max:255'],
            'scheduled_at'      => ['required', 'date', 'after:now'],
        ];
    }

    public function messages(): array
    {
        return [
            'volume_liters.gt'      => 'Jumlah liter bahan bakar harus lebih dari 0.',
            'scheduled_at.after'    => 'Jadwal pengiriman harus di masa mendatang.',
            'delivery_location.required' => 'Tujuan pengiriman tidak boleh kosong.',
        ];
    }
}
