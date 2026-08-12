<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name('female'),
            'treatment' => $this->faker->randomElement(['Massage Tradisional', 'Slimming Body Wrap', 'Post Natal Care', 'Herbal Bath', 'Totok Wajah']),
            'rating' => $this->faker->numberBetween(4, 5),
            'content' => $this->faker->paragraphs(2, true),
            'image' => null,
            'is_featured' => $this->faker->boolean(40),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
