<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Company;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    private static int $sequence = 0;

    public function definition(): array
    {
        self::$sequence++;

        $volume    = fake()->randomElement([5000, 8000, 10000, 15000, 20000]);
        $unitPrice = fake()->randomElement([10_500, 11_000, 11_500, 12_000]);

        return [
            'order_number'      => 'ORD/' . now()->format('Ym') . '/' . str_pad(self::$sequence, 4, '0', STR_PAD_LEFT),
            'company_id'        => Company::factory(),
            'created_by'        => User::factory(),
            'approved_by'       => null,
            'fuel_type'         => fake()->randomElement(['Solar B30', 'Pertalite', 'Pertamax', 'Solar Industri']),
            'volume_liters'     => $volume,
            'unit_price'        => $unitPrice,
            'delivery_location' => fake()->city() . ', ' . fake()->stateAbbr(),
            'scheduled_at'      => now()->addDays(fake()->numberBetween(3, 14)),
            'status'            => OrderStatus::DRAFT,
            'rejection_reason'  => null,
            'approved_at'       => null,
        ];
    }

    public function approved(User $approver): static
    {
        return $this->state(fn () => [
            'status'      => OrderStatus::APPROVED,
            'approved_by' => $approver->id,
            'approved_at' => now()->subHours(fake()->numberBetween(1, 48)),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => OrderStatus::PENDING_APPROVAL]);
    }

    public function waitingPayment(User $approver): static
    {
        return $this->state(fn () => [
            'status'      => OrderStatus::WAITING_PAYMENT,
            'approved_by' => $approver->id,
            'approved_at' => now()->subDays(1),
        ]);
    }

    public function forCompany(Company $company, User $creator): static
    {
        return $this->state(fn () => [
            'company_id' => $company->id,
            'created_by' => $creator->id,
        ]);
    }
}
