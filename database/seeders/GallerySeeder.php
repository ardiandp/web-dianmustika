<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Support\DummyImage;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $galleries = [
            ['tempat', 'Ruang perawatan Bintaro yang bersih dan nyaman', 'Ruang perawatan utama cabang Bintaro', 1, 'ruang-bintaro'],
            ['tempat', 'Resepsionis Dian Mustika', 'Area resepsionis dengan suasana hangat', 2, 'resepsionis'],
            ['treatment', 'Sesi massage tradisional', 'Suasana sesi massage bersama terapis', 3, 'sesi-massage'],
            ['treatment', 'Perawatan herbal bath', 'Sesi herbal bath dengan ramuan hangat', 4, 'herbal-bath-sesi'],
            ['treatment', 'Slimming body wrap', 'Proses perawatan slimming body wrap', 5, 'slimming-sesi'],
            ['treatment', 'Totok wajah oleh terapis', 'Terapis melakukan totok wajah', 6, 'totok-sesi'],
            ['tempat', 'Ruang tunggu yang nyaman', 'Ruang tunggu dengan interior menenangkan', 7, 'ruang-tunggu'],
            ['aktivitas', 'Pelatihan terapis', 'Kegiatan pelatihan tim terapis', 8, 'pelatihan-terapis'],
            ['aktivitas', 'Perayaan ulang tahun klien', 'Momen perayaan bersama pelanggan', 9, 'perayaan'],
            ['promo', 'Promo paket spa bulan ini', 'Informasi promo paket perawatan', 10, 'promo-spa'],
            ['tempat', 'Kamar perawatan post natal', 'Kamar khusus perawatan pasca melahirkan', 11, 'ruang-post-natal'],
            ['treatment', 'Perawatan lulur rempah', 'Proses lulur rempah tradisional', 12, 'lulur-sesi'],
        ];

        foreach ($galleries as [$category, $alt, $caption, $order, $seed]) {
            Gallery::create([
                'category' => $category,
                'image' => DummyImage::store('galleries', $seed, 800, 800),
                'alt_text' => $alt,
                'caption' => $caption,
                'is_active' => true,
                'sort_order' => $order,
            ]);
        }
    }
}
