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

    public function show(Package $package): View
    {
        abort_unless($package->is_active, 404);

        $package->load(['services' => fn ($q) => $q->active()->ordered()]);

        $seo = SeoService::for($package, [
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Paket & Promo', 'url' => route('packages.index')],
                    ['label' => $package->name, 'url' => route('packages.show', $package)],
                ]),
                SeoService::package($package),
            ],
        ]);

        return view('pages.packages.show', compact('package', 'seo'));
    }
}
