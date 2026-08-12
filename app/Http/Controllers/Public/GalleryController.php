<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
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

        return view('pages.galleries.index', compact('galleries', 'categories', 'current'));
    }
}
