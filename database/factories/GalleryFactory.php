<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement(['tempat', 'treatment', 'aktivitas', 'promo']),
            'image' => null,
            'alt_text' => $this->faker->sentence(4),
            'caption' => $this->faker->sentence(6),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
