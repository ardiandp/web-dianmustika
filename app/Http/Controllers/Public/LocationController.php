<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::active()->with('services')->ordered()->get();

        return view('pages.locations.index', compact('locations'));
    }

    public function show(Location $location): View
    {
        abort_unless($location->is_active, 404);

        $location->load(['services' => fn ($q) => $q->active()->ordered(), 'faqs' => fn ($q) => $q->active()]);

        return view('pages.locations.show', compact('location'));
    }
}
