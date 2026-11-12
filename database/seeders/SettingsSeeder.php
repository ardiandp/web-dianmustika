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
            'site_tagline' => 'Homecare Pascamelahirkan, Pijat Laktasi & Perawatan Ibu Bayi',
            'site_description' => 'Dian Mustika adalah pusat perawatan homecare pascamelahirkan, pijat laktasi, perawatan nifas, dan spa ibu & bayi profesional yang menghadirkan terapis berpengalaman langsung ke rumah Anda.',
            'default_meta_title' => 'Dian Mustika | Homecare Pascamelahirkan, Pijat Laktasi & Perawatan Ibu Bayi',
            'default_meta_description' => 'Layanan homecare pascamelahirkan profesional Dian Mustika: perawatan nifas tradisional & modern, pijat laktasi/breastcare, baby massage, dan spa ibu hamil di rumah Anda. Konsultasi gratis via WhatsApp.',
            'default_meta_keywords' => 'homecare pascamelahirkan, pijat nifas, pijat laktasi, breastcare, baby massage, bengkung tradisional, bidan homecare, dian mustika, spa ibu hamil',
            'default_og_image' => null,
            'logo' => null,
            'favicon' => null,
            'whatsapp' => '6281234567890',
            'phone' => '02112345678',
            'email' => 'halo@dianmustika.co.id',
            'address' => 'Bintaro, Tangerang Selatan, Indonesia',
            'opening_hours' => json_encode([
                'Senin - Jumat' => '09.00 - 21.00',
                'Sabtu - Minggu' => '09.00 - 22.00',
            ]),
            'social_instagram' => 'https://instagram.com/dianmustika',
            'social_facebook' => 'https://facebook.com/dianmustika',
            'social_tiktok' => 'https://tiktok.com/@dianmustika',
            'about_heading' => 'Tentang Dian Mustika',
            'about_text' => 'Dian Mustika adalah pusat perawatan tubuh dan kecantikan yang hadir untuk membantu Anda merawat diri dengan layanan profesional, nyaman, dan elegan. Kami menggabungkan teknik perawatan modern dengan kearifan tradisional untuk memberikan pengalaman perawatan terbaik bagi setiap pelanggan.',
            'google_maps_embed' => null,
            'footer_copyright' => '© '.date('Y').' Dian Mustika. Seluruh hak cipta dilindungi.',
            'chat_ai_enabled' => '1',
            'chat_ai_welcome' => 'Halo! Saya Mika, asisten virtual Dian Mustika. Ada yang bisa saya bantu seputar layanan, paket, promo, cara reservasi, atau jam operasional?',
            'chat_ai_sources' => json_encode(['layanan', 'paket', 'faq', 'lokasi', 'artikel']),
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
