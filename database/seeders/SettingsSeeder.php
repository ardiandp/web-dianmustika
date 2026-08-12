<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Dian Mustika',
            'site_tagline' => 'Perawatan Tubuh & Kecantikan',
            'site_description' => 'Dian Mustika adalah pusat perawatan tubuh dan kecantikan yang membantu Anda merawat diri dengan layanan profesional, nyaman, dan elegan.',
            'whatsapp' => '6281234567890',
            'phone' => '02112345678',
            'email' => 'halo@dianmustika.co.id',
            'address' => 'Bintaro, Tangerang Selatan, Indonesia',
            'opening_hours' => json_encode([
                'Senin - Jumat' => '09.00 - 21.00',
                'Sabtu - Minggu' => '09.00 - 22.00',
            ]),
            'social_media' => json_encode([
                'instagram' => 'https://instagram.com/dianmustika',
                'facebook' => 'https://facebook.com/dianmustika',
                'tiktok' => 'https://tiktok.com/@dianmustika',
            ]),
            'google_maps_embed' => null,
            'footer_copyright' => '© ' . date('Y') . ' Dian Mustika. Seluruh hak cipta dilindungi.',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
