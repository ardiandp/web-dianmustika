<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Location;
use App\Models\Service;
use Illuminate\Database\Seeder;

class SeoMetadataSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'model' => Service::where('slug', 'massage-tradisional')->first(),
                'data' => [
                    'title' => 'Massage Tradisional Bali | Dian Mustika',
                    'description' => 'Pijat tradisional Bali dengan teknik turun-temurun untuk meredakan pegal, melancarkan peredaran darah, dan memulihkan energi tubuh.',
                    'keywords' => 'massage tradisional, pijat bali, pijat relaksasi, terapi tubuh, dian mustika',
                ],
            ],
            [
                'model' => Service::where('slug', 'slimming-body-wrap')->first(),
                'data' => [
                    'title' => 'Slimming Body Wrap | Program Body Slimming Dian Mustika',
                    'description' => 'Program body slimming dengan body wrap yang membantu mengencangkan dan mengecilkan lingkar tubuh secara alami.',
                ],
            ],
            [
                'model' => Article::where('slug', '7-manfaat-pijat-relaksasi-untuk-kesehatan-tubuh-dan-pikiran')->first(),
                'data' => [
                    'title' => '7 Manfaat Pijat Relaksasi untuk Kesehatan Tubuh dan Pikiran',
                    'description' => 'Pijat relaksasi bukan sekadar melepas lelah. Kenali 7 manfaatnya bagi kesehatan tubuh dan pikiran dalam artikel berikut.',
                ],
            ],
            [
                'model' => Location::where('slug', 'dian-mustika-bintaro')->first(),
                'data' => [
                    'title' => 'Dian Mustika Bintaro | Salon & Spa Tangerang Selatan',
                    'description' => 'Cabang utama Dian Mustika di Bintaro, Tangerang Selatan. Perawatan tubuh dan kecantikan profesional dengan terapis berpengalaman.',
                    'canonical' => route('locations.show', ['location' => 'dian-mustika-bintaro']),
                ],
            ],
        ];

        foreach ($rows as $row) {
            if (! $row['model']) {
                continue;
            }

            $row['model']->seo()->updateOrCreate([], $row['data']);
        }
    }
}
