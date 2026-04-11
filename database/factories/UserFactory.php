<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'company_id'        => null,
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'role'              => 'staf_pembeli',
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function adminPenjualan(): static
    {
        return $this->state(fn () => [
            'role'       => 'admin_penjualan',
            'company_id' => null,
        ]);
    }

    public function manajerPembeli(Company $company): static
    {
        return $this->state(fn () => [
            'role'       => 'manajer_pembeli',
            'company_id' => $company->id,
        ]);
    }

    public function stafPembeli(Company $company): static
    {
        return $this->state(fn () => [
            'role'       => 'staf_pembeli',
            'company_id' => $company->id,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
