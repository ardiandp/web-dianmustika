<?php

namespace Database\Factories;

use App\Models\ArticleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleCategoryFactory extends Factory
{
    protected $model = ArticleCategory::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Perawatan Tubuh',
            'Kecantikan',
            'Slimming',
            'Tips Kesehatan',
            'Lifestyle',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
