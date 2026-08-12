<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Services\SeoService;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::active()->with('services')->ordered()->get();

        $featured = $packages->where('is_featured', true)->take(3);
        $rest = $packages->where('is_featured', false);

        $seo = SeoService::forPage([
            'title' => 'Paket & Promo',
            'description' => 'Lihat paket perawatan dan promo terbaik Dian Mustika untuk perawatan tubuh dan kecantikan dengan harga spesial.',
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Paket & Promo', 'url' => route('packages.index')],
                ]),
            ],
        ]);

        return view('pages.packages.index', compact('packages', 'featured', 'rest', 'seo'));
    }
}
