<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 50);
        $price = $this->faker->randomFloat(2, 100000, 10000000);
        return [
            'name' => 'Asset ' . $this->faker->word(),
            'category' => $this->faker->randomElement(['Elektronik', 'Kendaraan', 'Peralatan']),
            'location' => $this->faker->city(),
            'description' => $this->faker->optional()->sentence(),
            'asset_code' => strtoupper(Str::random(8)),
            'price' => $price,
            'quantity' => $quantity,
            'amount' => $price * $quantity,
            'established_at' => Carbon::now()->subYears(rand(0, 5))->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
