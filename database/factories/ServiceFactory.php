<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        $price = $this->faker->numberBetween(100, 600) * 1000;

        return [
            'service_category_id' => ServiceCategory::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'short_description' => $this->faker->sentence(8),
            'description' => $this->faker->paragraphs(3, true),
            'benefits' => $this->faker->sentences(4),
            'duration' => $this->faker->randomElement(['45 menit', '60 menit', '90 menit', '120 menit']),
            'price' => $this->faker->boolean(20) ? null : $price,
            'note' => $this->faker->boolean(40) ? $this->faker->sentence() : null,
            'image' => null,
            'alt_text' => ucwords($name),
            'is_featured' => $this->faker->boolean(20),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
