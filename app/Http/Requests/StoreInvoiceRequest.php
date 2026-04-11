<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $order = $this->route('order');

        // InvoicePolicy::create(User, Order) is registered under Invoice::class
        return $this->user()->can('create', [Invoice::class, $order]);
    }

    public function rules(): array
    {
        return [];
    }
}
