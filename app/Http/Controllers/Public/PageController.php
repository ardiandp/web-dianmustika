<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Location;
use App\Models\Service;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $services = Service::active()->ordered()->limit(6)->get();
        $locations = Location::active()->ordered()->get();

        return view('pages.about', compact('services', 'locations'));
    }

    public function faqs(): View
    {
        $faqs = Faq::active()->ordered()->get();
        $grouped = $faqs->groupBy('category');

        return view('pages.faqs.index', compact('faqs', 'grouped'));
    }

    public function contact(): View
    {
        $locations = Location::active()->ordered()->get();

        return view('pages.contact.index', compact('locations'));
    }
}
