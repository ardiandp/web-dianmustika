<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Location;
use App\Models\Service;
use App\Services\SeoService;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $services = Service::active()->with('category')->ordered()->limit(6)->get();
        $locations = Location::active()->ordered()->get();

        $seo = SeoService::forPage([
            'title' => 'Tentang Kami',
            'description' => 'Kenali lebih dekat Dian Mustika, pusat perawatan tubuh dan kecantikan yang menggabungkan teknik modern dengan kearifan tradisional.',
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Tentang Kami', 'url' => route('about')],
                ]),
                SeoService::organization(),
            ],
        ]);

        return view('pages.about', compact('services', 'locations', 'seo'));
    }

    public function faqs(): View
    {
        $faqs = Faq::active()->ordered()->get();
        $grouped = $faqs->groupBy('category');

        $seo = SeoService::forPage([
            'title' => 'FAQ',
            'description' => 'Pertanyaan yang sering diajukan tentang layanan, harga, dan prosedur perawatan di Dian Mustika.',
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'FAQ', 'url' => route('faqs.index')],
                ]),
                SeoService::faq($faqs),
            ],
        ]);

        return view('pages.faqs.index', compact('faqs', 'grouped', 'seo'));
    }

    public function contact(): View
    {
        $locations = Location::active()->ordered()->get();

        $seo = SeoService::forPage([
            'title' => 'Kontak',
            'description' => 'Hubungi Dian Mustika untuk konsultasi dan reservasi perawatan via WhatsApp, telepon, atau kunjungi cabang terdekat.',
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Kontak', 'url' => route('contact.index')],
                ]),
                SeoService::organization(),
            ],
        ]);

        return view('pages.contact.index', compact('locations', 'seo'));
    }
}
