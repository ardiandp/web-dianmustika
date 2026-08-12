<?php

namespace Database\Seeders;

use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;

class ArticleCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Perawatan Tubuh', 'Informasi seputar perawatan tubuh dan relaksasi.', 1],
            ['Kecantikan', 'Tips dan informasi menjaga kecantikan kulit.', 2],
            ['Slimming', 'Program dan tips mencapai tubuh ideal.', 3],
            ['Tips Kesehatan', 'Berbagai tips menjaga kesehatan tubuh.', 4],
            ['Lifestyle', 'Gaya hidup sehat dan seimbang.', 5],
        ];

        foreach ($categories as [$name, $description, $order]) {
            ArticleCategory::updateOrCreate(
                ['slug' => str()->slug($name)],
                [
                    'name' => $name,
                    'description' => $description,
                    'is_active' => true,
                    'sort_order' => $order,
                ]
            );
        }
    }
}
