<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Location;
use App\Models\Package;
use App\Models\Service;
use App\Models\Testimonial;
use App\Services\SeoService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $services = Service::active()->featured()->with('category')->ordered()->limit(6)->get();
        $packages = Package::active()->ordered()->limit(6)->get();
        $locations = Location::active()->ordered()->limit(3)->get();
        $testimonials = Testimonial::active()->featured()->ordered()->limit(6)->get();
        $articles = Article::published()->active()->with('category')->ordered()->limit(3)->get();
        $galleries = Gallery::active()->ordered()->limit(8)->get();

        $seo = SeoService::forPage([
            'description' => \App\Models\Setting::get('default_meta_description', \App\Models\Setting::get('site_description', '')),
            'schema' => [
                SeoService::webSite(),
                SeoService::organization(),
            ],
        ]);

        return view('pages.home', compact('services', 'packages', 'locations', 'testimonials', 'articles', 'galleries', 'seo'));
    }
}
