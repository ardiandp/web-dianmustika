<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        $price = $this->faker->numberBetween(300, 2500) * 1000;

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(2, true),
            'price' => $price,
            'promo_price' => $this->faker->boolean(80) ? (int) round($price * 0.85) : null,
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->addMonths(2),
            'image' => null,
            'alt_text' => ucwords($name),
            'is_featured' => $this->faker->boolean(30),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
