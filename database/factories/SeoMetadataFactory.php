<?php

namespace Database\Factories;

use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeoMetadata>
 */
class SeoMetadataFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(4, true),
            'description' => $this->faker->sentence(12),
            'robots' => 'index, follow',
        ];
    }
}
