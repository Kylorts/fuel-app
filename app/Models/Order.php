<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'company_id',
        'created_by',
        'approved_by',
        'fuel_type',
        'volume_liters',
        'unit_price',
        'delivery_location',
        'scheduled_at',
        'status',
        'rejection_reason',
        'approved_at',
    ];

    protected $casts = [
        'volume_liters' => 'decimal:2',
        'unit_price'    => 'decimal:2',
        'scheduled_at'  => 'datetime',
        'approved_at'   => 'datetime',
        'status'        => OrderStatus::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function isApproved(): bool
    {
        return $this->status === OrderStatus::APPROVED;
    }

    public function hasInvoice(): bool
    {
        return $this->invoice()->exists();
    }

    public function subtotal(): float
    {
        return (float) bcmul($this->volume_liters, $this->unit_price, 2);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::APPROVED->value);
    }

    public function scopeWaitingPayment(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::WAITING_PAYMENT->value);
    }
}
