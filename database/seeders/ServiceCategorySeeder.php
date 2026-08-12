<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Massage & Relaksasi', 'Beragam teknik pijat untuk melepaskan penat, meredakan ketegangan otot, dan mengembalikan energi tubuh.', 1],
            ['Slimming & Body Care', 'Program perawatan tubuh untuk membantu membentuk tubuh ideal dan menjaga kesehatan kulit.', 2],
            ['Perawatan Pasca Melahirkan', 'Perawatan khusus untuk membantu pemulihan ibu setelah melahirkan secara menyeluruh.', 3],
            ['Herbal & Tradisional', 'Perawatan berbahan dasar herbal dan metode tradisional yang menenangkan tubuh dan pikiran.', 4],
            ['Perawatan Wajah', 'Perawatan kulit wajah untuk menjaga kesehatan serta kecantikan kulit secara alami.', 5],
            ['Homecare', 'Layanan perawatan di rumah dengan terapis profesional dan peralatan yang aman.', 6],
        ];

        foreach ($categories as [$name, $description, $order]) {
            ServiceCategory::updateOrCreate(
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
