<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Services\SeoService;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::active()->with('services')->ordered()->get();

        $seo = SeoService::forPage([
            'title' => 'Lokasi & Cabang',
            'description' => 'Temukan cabang Dian Mustika terdekat untuk perawatan tubuh dan kecantikan. Alamat, jam operasional, dan kontak lengkap tersedia di sini.',
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Lokasi', 'url' => route('locations.index')],
                ]),
            ],
        ]);

        return view('pages.locations.index', compact('locations', 'seo'));
    }

    public function show(Location $location): View
    {
        abort_unless($location->is_active, 404);

        $location->load(['services' => fn ($q) => $q->active()->ordered(), 'faqs' => fn ($q) => $q->active()]);

        $seo = SeoService::for($location, [
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Lokasi', 'url' => route('locations.index')],
                    ['label' => $location->name, 'url' => route('locations.show', $location)],
                ]),
                SeoService::beautySalon($location),
            ],
        ]);

        if ($location->faqs->isNotEmpty()) {
            $seo['schema'][] = SeoService::faq($location->faqs);
        }

        return view('pages.locations.show', compact('location', 'seo'));
    }
}
