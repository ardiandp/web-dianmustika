<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(5);

        return [
            'article_category_id' => ArticleCategory::factory(),
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->sentence(12),
            'content' => $this->faker->paragraphs(6, true),
            'featured_image' => null,
            'alt_text' => $title,
            'is_featured' => $this->faker->boolean(25),
            'is_active' => true,
            'published_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
