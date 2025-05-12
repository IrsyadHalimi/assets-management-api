<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Category;


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
        $quantity = $this->faker->numberBetween(1, 10);
        $price = $this->faker->randomFloat(2, 100000, 5000000);
        return [
            'name' => 'Asset ' . $this->faker->word(),
            'category_id' => Category::inRandomOrder()->first()->id,
            'location' => $this->faker->city(),
            'description' => $this->faker->optional()->sentence(),
            'asset_code' => strtoupper(Str::random(8)),
            'price' => $price,
            'quantity' => $quantity,
            'amount' => round($price * $quantity, 2),
            'established_at' => Carbon::now()->subYears(rand(0, 5))->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
