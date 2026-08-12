<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $categories = ServiceCategory::active()->ordered()->withCount(['services' => fn ($q) => $q->active()])->get();

        $services = Service::query()
            ->active()
            ->when($request->filled('kategori'), fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $request->string('kategori'))))
            ->with('category')
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        return view('pages.services.index', compact('services', 'categories'));
    }

    public function show(Service $service): View
    {
        abort_unless($service->is_active, 404);

        $service->load(['category', 'faqs' => fn ($q) => $q->active(), 'locations' => fn ($q) => $q->active()]);

        $related = Service::active()
            ->where('id', '!=', $service->id)
            ->when($service->service_category_id, fn ($q) => $q->where('service_category_id', $service->service_category_id))
            ->ordered()
            ->limit(3)
            ->get();

        return view('pages.services.show', compact('service', 'related'));
    }
}
