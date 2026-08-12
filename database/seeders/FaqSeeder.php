<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['umum', 'Apakah perlu reservasi sebelum datang?', 'Kami menyarankan untuk melakukan reservasi terlebih dahulu melalui WhatsApp agar jadwal perawatan Anda dapat diatur dengan baik dan tidak perlu menunggu lama saat datang.', null, null, 1],
            ['umum', 'Bagaimana cara menghubungi Dian Mustika?', 'Anda dapat menghubungi kami melalui WhatsApp, telepon, atau email yang tertera di halaman kontak. Tim kami siap membantu menjawab pertanyaan Anda.', null, null, 2],
            ['umum', 'Apakah layanan Dian Mustika hanya untuk perempuan?', 'Layanan kami dikhususkan untuk perempuan dengan terapis perempuan, sehingga perawatan terasa aman dan nyaman.', null, null, 3],
            ['layanan', 'Layanan apa saja yang tersedia di Dian Mustika?', 'Kami menyediakan berbagai layanan seperti massage, slimming, totok wajah, herbal bath, perawatan pasca melahirkan, hingga layanan homecare. Lihat halaman layanan untuk daftar lengkapnya.', null, null, 4],
            ['layanan', 'Apakah tersedia layanan homecare?', 'Ya, kami menyediakan layanan homecare dengan terapis yang datang langsung ke rumah Anda. Tersedia homecare massage dan homecare post natal.', null, null, 5],
            ['layanan', 'Apakah pijat aman untuk ibu hamil?', 'Pijat ibu hamil di Dian Mustika dilakukan oleh terapis berpengalaman dengan teknik lembut yang aman, terutama pada trimester kedua dan ketiga. Konsultasikan kondisi kehamilan Anda terlebih dahulu.', null, null, 6],
            ['layanan', 'Kapan waktu terbaik untuk perawatan pasca melahirkan?', 'Umumnya perawatan dapat dimulai setelah kondisi ibu dinyatakan cukup stabil oleh tenaga medis. Konsultasikan dengan terapis kami untuk waktu yang paling tepat.', null, null, 7],
            ['harga', 'Bagaimana sistem pembayarannya?', 'Pembayaran dapat dilakukan langsung di tempat melalui transfer atau tunai. Detail pembayaran akan diinformasikan saat reservasi.', null, null, 8],
            ['harga', 'Apakah tersedia paket atau promo?', 'Ya, kami memiliki berbagai paket perawatan dengan harga spesial. Lihat halaman paket untuk informasi promo yang sedang berjalan.', null, null, 9],
            ['harga', 'Apakah harga sudah termasuk semua biaya?', 'Harga yang tertera adalah harga perawatan. Untuk layanan homecare, sudah termasuk biaya transport terapis. Informasi lengkap dapat dikonfirmasi saat reservasi.', null, null, 10],
            ['lokasi', 'Di mana saja lokasi Dian Mustika?', 'Saat ini kami memiliki cabang di Bintaro, Tangerang, dan Balikpapan. Lihat halaman lokasi untuk alamat dan jam operasional lengkap.', null, null, 11],
            ['lokasi', 'Apakah setiap cabang memiliki layanan yang sama?', 'Sebagian besar layanan tersedia di semua cabang. Untuk memastikan ketersediaan layanan tertentu, silakan hubungi cabang yang bersangkutan.', null, null, 12],
            ['perawatan', 'Apakah perawatan menggunakan bahan alami?', 'Sebagian besar perawatan kami menggunakan bahan-bahan alami seperti rempah tradisional. Detail bahan dapat dikonfirmasi kepada terapis sebelum perawatan.', null, null, 13],
            ['perawatan', 'Bagaimana jika saya memiliki kulit sensitif?', 'Informasikan kepada terapis mengenai kondisi kulit Anda sebelum perawatan. Terapis akan menyesuaikan produk dan teknik yang digunakan agar aman dan nyaman.', null, null, 14],
        ];

        foreach ($faqs as [$category, $question, $answer, $serviceId, $locationId, $order]) {
            Faq::updateOrCreate(
                ['question' => $question],
                [
                    'category' => $category,
                    'answer' => $answer,
                    'service_id' => $serviceId,
                    'location_id' => $locationId,
                    'is_active' => true,
                    'sort_order' => $order,
                ]
            );
        }
    }
}
