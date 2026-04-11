<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'company_id', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function approvedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'approved_by');
    }

    public function issuedInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'issued_by');
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    public function isAdminPenjualan(): bool
    {
        return $this->role === 'admin_penjualan';
    }

    public function isManajerPembeli(): bool
    {
        return $this->role === 'manajer_pembeli';
    }

    public function isStafPembeli(): bool
    {
        return $this->role === 'staf_pembeli';
    }

    public function isPetugasSpbu(): bool
    {
        return $this->role === 'petugas_spbu';
    }
}
