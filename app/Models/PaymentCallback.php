<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentCallback extends Model
{
    protected $fillable = [
        'virtual_account_id',
        'bank_reference',
        'amount_received',
        'received_at',
        'is_valid',
        'error_message',
    ];

    protected $casts = [
        'amount_received' => 'decimal:2',
        'received_at'     => 'datetime',
        'is_valid'        => 'boolean',
    ];

    public function virtualAccount(): BelongsTo
    {
        return $this->belongsTo(VirtualAccount::class);
    }
}
