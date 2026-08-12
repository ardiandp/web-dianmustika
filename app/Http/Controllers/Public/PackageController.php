<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::active()->with('services')->ordered()->get();

        $featured = $packages->where('is_featured', true)->take(3);
        $rest = $packages->where('is_featured', false);

        return view('pages.packages.index', compact('packages', 'featured', 'rest'));
    }
}
