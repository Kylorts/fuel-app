<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'order_id',
        'issued_by',
        'subtotal',
        'ppn_rate',
        'ppn_amount',
        'total_amount',
        'status',
        'pdf_path',
        'issued_at',
        'paid_at',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'ppn_rate'     => 'decimal:4',
        'ppn_amount'   => 'decimal:2',
        'total_amount' => 'decimal:2',
        'status'       => InvoiceStatus::class,
        'issued_at'    => 'datetime',
        'paid_at'      => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function virtualAccounts(): HasMany
    {
        return $this->hasMany(VirtualAccount::class);
    }

    public function markAsPaid(Carbon $paidAt): void
    {
        $this->update([
            'status'  => InvoiceStatus::PAID,
            'paid_at' => $paidAt,
        ]);
    }

    public function activeVirtualAccount(): ?VirtualAccount
    {
        return $this->virtualAccounts()->where('status', 'active')->first();
    }

    public function scopeIssued(Builder $query): Builder
    {
        return $query->where('status', InvoiceStatus::ISSUED->value);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', InvoiceStatus::PAID->value);
    }
}
