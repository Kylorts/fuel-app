<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        $npwp = implode('.', [
            str_pad(fake()->numerify('##'), 2, '0', STR_PAD_LEFT),
            str_pad(fake()->numerify('###'), 3, '0', STR_PAD_LEFT),
            str_pad(fake()->numerify('###'), 3, '0', STR_PAD_LEFT),
            fake()->numerify('#') . '-' . str_pad(fake()->numerify('###'), 3, '0', STR_PAD_LEFT) . '.' . str_pad(fake()->numerify('###'), 3, '0', STR_PAD_LEFT),
        ]);

        return [
            'name'    => 'PT ' . fake()->company(),
            'email'   => fake()->companyEmail(),
            'phone'   => '021' . fake()->numerify('#######'),
            'address' => fake()->address(),
            'npwp'    => $npwp,
        ];
    }
}
