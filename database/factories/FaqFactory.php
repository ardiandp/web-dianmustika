<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement(['umum', 'layanan', 'harga', 'lokasi', 'perawatan']),
            'question' => $this->faker->sentence(6).'?',
            'answer' => $this->faker->paragraphs(2, true),
            'service_id' => null,
            'location_id' => null,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
