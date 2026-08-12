<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = ['tempat', 'treatment', 'aktivitas', 'promo'];

        $galleries = Gallery::active()
            ->when($request->filled('kategori'), fn ($q) => $q->where('category', $request->string('kategori')))
            ->ordered()
            ->get();

        $current = $request->string('kategori', '');

        $seo = SeoService::forPage([
            'title' => 'Galeri',
            'description' => 'Lihat suasana, fasilitas, dan hasil perawatan di Dian Mustika melalui galeri foto tempat dan treatment.',
            'robots' => 'index, follow',
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Galeri', 'url' => route('galleries.index')],
                ]),
            ],
        ]);

        return view('pages.galleries.index', compact('galleries', 'categories', 'current', 'seo'));
    }
}
