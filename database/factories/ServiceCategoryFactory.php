<?php

namespace Database\Factories;

use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceCategoryFactory extends Factory
{
    protected $model = ServiceCategory::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Massage & Relaksasi',
            'Slimming & Body Care',
            'Perawatan Pasca Melahirkan',
            'Herbal & Tradisional',
            'Perawatan Wajah',
            'Homecare',
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
