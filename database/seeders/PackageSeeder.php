<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Service;
use App\Support\DummyImage;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'Paket Relaxation Spa',
                'Kombinasi perawatan untuk relaksasi menyeluruh: pijat, herbal bath, dan lulur dalam satu paket.',
                550000,
                450000,
                now()->subDays(5),
                now()->addDays(60),
                true,
                1,
                'paket-relaxation-spa',
                ['massage-tradisional', 'herbal-bath', 'lulur-rempah'],
            ],
            [
                'Paket Slimming 10x',
                'Program slimming body wrap 10 sesi dengan pendampingan untuk hasil yang lebih optimal.',
                2500000,
                2000000,
                now()->subDays(3),
                now()->addMonths(3),
                true,
                2,
                'paket-slimming-10x',
                ['slimming-body-wrap', 'program-slimming-v-shape'],
            ],
            [
                'Paket Post Natal Lengkap',
                'Rangkaian perawatan pasca melahirkan: pijat ibu hamil, post natal care, dan perawatan payudara.',
                900000,
                750000,
                now()->subDays(1),
                now()->addMonths(2),
                true,
                3,
                'paket-post-natal',
                ['pijat-ibu-hamil', 'post-natal-care', 'perawatan-payudara-pantang'],
            ],
            [
                'Paket Glowing',
                'Perawatan wajah lengkap dengan totok wajah dan facial untuk kulit yang sehat bercahaya.',
                320000,
                270000,
                now()->subDays(10),
                now()->addDays(45),
                false,
                4,
                'paket-glowing',
                ['totok-wajah', 'facial-lulur-wajah'],
            ],
            [
                'Paket Homecare Bulanan',
                'Layanan homecare massage 4 sesi per bulan untuk Anda yang sibuk namun tetap ingin merawat diri.',
                1400000,
                1200000,
                now()->subDays(7),
                now()->addMonths(2),
                false,
                5,
                'paket-homecare',
                ['homecare-massage'],
            ],
            [
                'Paket Healing Weekend',
                'Paket akhir pekan untuk melepas penat: aromatherapy, pijat refleksi, dan herbal bath.',
                500000,
                420000,
                now()->subDays(2),
                now()->addDays(30),
                false,
                6,
                'paket-healing-weekend',
                ['aromaterapi-massage', 'pijat-refleksi', 'herbal-bath'],
            ],
        ];

        foreach ($packages as [$name, $desc, $price, $promo, $start, $end, $featured, $order, $seed, $serviceSlugs]) {
            $package = Package::updateOrCreate(
                ['slug' => str()->slug($name)],
                [
                    'name' => $name,
                    'description' => $desc,
                    'price' => $price,
                    'promo_price' => $promo,
                    'starts_at' => $start,
                    'ends_at' => $end,
                    'image' => DummyImage::store('packages', $seed, 900, 600),
                    'alt_text' => $name,
                    'is_featured' => $featured,
                    'is_active' => true,
                    'sort_order' => $order,
                ]
            );

            $serviceIds = Service::whereIn('slug', $serviceSlugs)->pluck('id')->all();
            $package->services()->sync($serviceIds);
        }
    }
}
