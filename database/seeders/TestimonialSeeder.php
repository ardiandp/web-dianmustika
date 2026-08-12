<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Support\DummyImage;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'Sinta Rahayu',
                'Post Natal Care',
                'Setelah melahirkan badan saya terasa sangat pegal. Terapisnya sabar dan berpengalaman, setelah beberapa sesi Post Natal Care badan saya terasa jauh lebih ringan dan nyaman.',
                5,
                true,
                1,
                'sinta',
            ],
            [
                'Dewi Lestari',
                'Massage Tradisional',
                'Pijatnya enak banget dan tegasnya pas. Setelah kerja seharian, satu sesi massage tradisional di Dian Mustika benar-benar mengembalikan tenaga saya.',
                5,
                true,
                2,
                'dewi',
            ],
            [
                'Ratna Anggraini',
                'Slimming Body Wrap',
                'Sudah 6 kali perawatan slimming di sini, hasilnya mulai terlihat. Perawatannya nyaman dan tempatnya selalu bersih. Recommended!',
                5,
                true,
                3,
                'ratna',
            ],
            [
                'Fitri Handayani',
                'Herbal Bath',
                'Herbal bath-nya hangat dan wangi rempahnya menyegarkan. Badan langsung rileks dan tidur pun jadi nyenyak. Pelayanannya ramah.',
                5,
                true,
                4,
                'fitri',
            ],
            [
                'Nadia Putri',
                'Aromatherapy Massage',
                'Suasananya tenang, aromanya bikin rileks. Saya rutin datang setiap bulan untuk menjaga kondisi tubuh. Terapisnya juga ramah dan profesional.',
                5,
                false,
                5,
                'nadia',
            ],
            [
                'Yuni Safitri',
                'Totok Wajah',
                'Wajah saya terasa lebih segar setelah totok wajah. Dilakukan dengan teknik yang tepat, tidak sakit. Sangat puas dengan hasilnya.',
                4,
                false,
                6,
                'yuni',
            ],
            [
                'Mega Puspita',
                'Homecare Massage',
                'Buat ibu baru seperti saya, layanan homecare sangat membantu. Terapis datang tepat waktu dan perawatannya tetap nyaman meskipun di rumah.',
                5,
                false,
                7,
                'mega',
            ],
            [
                'Laras Ayu',
                'Facial & Lulur Wajah',
                'Kulit wajah saya jadi lebih cerah dan bersih. Perawatan facial di sini lengkap dan hasilnya benar-benar terlihat.',
                5,
                false,
                8,
                'laras',
            ],
        ];

        foreach ($testimonials as [$name, $treatment, $content, $rating, $featured, $order, $seed]) {
            Testimonial::create([
                'customer_name' => $name,
                'treatment' => $treatment,
                'rating' => $rating,
                'content' => $content,
                'image' => DummyImage::avatar($seed),
                'is_featured' => $featured,
                'is_active' => true,
                'sort_order' => $order,
            ]);
        }
    }
}
