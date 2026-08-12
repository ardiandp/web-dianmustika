<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Service;
use App\Support\DummyImage;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'Dian Mustika Bintaro',
                'Ruko Jalan Boulevard Raya Blok A No. 12, Bintaro Jaya, Tangerang Selatan, Banten 15224',
                'Cabang utama Dian Mustika di kawasan Bintaro Jaya. Terdapat ruang perawatan yang nyaman dan privat untuk berbagai layanan perawatan tubuh, wajah, dan pasca melahirkan.',
                '(021) 745 7788',
                '6281234567890',
                'bintaro@dianmustika.co.id',
                'https://maps.google.com/?q=Bintaro+Jaya+Tangerang+Selatan',
                [
                    'Senin' => '09.00 - 21.00',
                    'Selasa' => '09.00 - 21.00',
                    'Rabu' => '09.00 - 21.00',
                    'Kamis' => '09.00 - 21.00',
                    'Jumat' => '09.00 - 21.00',
                    'Sabtu' => '09.00 - 22.00',
                    'Minggu' => '09.00 - 22.00',
                ],
                true,
                1,
                'lokasi-bintaro',
                ['massage-tradisional', 'aromaterapi-massage', 'slimming-body-wrap', 'post-natal-care', 'herbal-bath'],
            ],
            [
                'Dian Mustika Tangerang',
                'Ruko CBD Emerald Blok C No. 5, Karawaci, Tangerang, Banten 15116',
                'Cabang Dian Mustika di kawasan Karawaci Tangerang dengan fasilitas perawatan lengkap dan terapis berpengalaman untuk seluruh keluarga.',
                '(021) 556 1122',
                '6289876543210',
                'tangerang@dianmustika.co.id',
                'https://maps.google.com/?q=Karawaci+Tangerang',
                [
                    'Senin' => '09.00 - 21.00',
                    'Selasa' => '09.00 - 21.00',
                    'Rabu' => '09.00 - 21.00',
                    'Kamis' => '09.00 - 21.00',
                    'Jumat' => '09.00 - 21.00',
                    'Sabtu' => '09.00 - 22.00',
                    'Minggu' => '09.00 - 22.00',
                ],
                false,
                2,
                'lokasi-tangerang',
                ['deep-tissue-massage', 'pijat-refleksi', 'facial-lulur-wajah', 'lulur-rempah', 'homecare-massage'],
            ],
            [
                'Dian Mustika Balikpapan',
                'Jl. Jenderal Sudirman No. 88, Balikpapan, Kalimantan Timur 76114',
                'Cabang Dian Mustika di Balikpapan menyediakan layanan perawatan tubuh dan kecantikan dengan standar kualitas yang sama dengan cabang lainnya.',
                '(0542) 733 4455',
                '6285551234567',
                'balikpapan@dianmustika.co.id',
                'https://maps.google.com/?q=Balikpapan+Kalimantan+Timur',
                [
                    'Senin' => '09.00 - 21.00',
                    'Selasa' => '09.00 - 21.00',
                    'Rabu' => '09.00 - 21.00',
                    'Kamis' => '09.00 - 21.00',
                    'Jumat' => '09.00 - 21.00',
                    'Sabtu' => '09.00 - 22.00',
                    'Minggu' => '09.00 - 22.00',
                ],
                false,
                3,
                'lokasi-balikpapan',
                ['massage-tradisional', 'slimming-body-wrap', 'totok-wajah', 'pijat-ibu-hamil', 'herbal-bath'],
            ],
        ];

        foreach ($locations as [$name, $address, $desc, $phone, $whatsapp, $email, $maps, $hours, $featured, $order, $seed, $serviceSlugs]) {
            $location = Location::updateOrCreate(
                ['slug' => str()->slug($name)],
                [
                    'name' => $name,
                    'address' => $address,
                    'description' => $desc,
                    'phone' => $phone,
                    'whatsapp' => $whatsapp,
                    'email' => $email,
                    'google_maps_url' => $maps,
                    'google_maps_embed' => null,
                    'opening_hours' => $hours,
                    'image' => DummyImage::store('locations', $seed, 1200, 800),
                    'alt_text' => $name,
                    'is_active' => true,
                    'sort_order' => $order,
                ]
            );

            $serviceIds = Service::whereIn('slug', $serviceSlugs)->pluck('id')->all();
            $location->services()->sync($serviceIds);
        }
    }
}
