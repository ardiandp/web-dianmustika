<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\SeoService;
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

        $seo = SeoService::forPage([
            'title' => 'Layanan',
            'description' => 'Jelajahi berbagai layanan perawatan tubuh dan kecantikan di Dian Mustika: massage, lulur, facial, slimming, dan treatment profesional.',
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Layanan', 'url' => route('services.index')],
                ]),
            ],
        ]);

        return view('pages.services.index', compact('services', 'categories', 'seo'));
    }

    public function show(Service $service): View
    {
        abort_unless($service->is_active, 404);

        $service->load([
            'category',
            'faqs' => fn ($q) => $q->active()->ordered(),
            'locations' => fn ($q) => $q->active()->ordered(),
            'galleries' => fn ($q) => $q->active()->ordered(),
            'relatedServices' => fn ($q) => $q->active()->with('category'),
            'articles' => fn ($q) => $q->active()->published()->with('category'),
        ]);

        // Prefer manual related services, fallback to same category
        $related = $service->relatedServices;
        if ($related->isEmpty()) {
            $related = Service::active()
                ->where('id', '!=', $service->id)
                ->when($service->service_category_id, fn ($q) => $q->where('service_category_id', $service->service_category_id))
                ->with('category')
                ->ordered()
                ->limit(3)
                ->get();
        } else {
            $related = $related->take(3);
        }

        // Related articles via pivot
        $relatedArticles = $service->articles->take(3);

        $seo = SeoService::for($service, [
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Layanan', 'url' => route('services.index')],
                    ['label' => $service->name, 'url' => route('services.show', $service)],
                ]),
                SeoService::service($service),
            ],
        ]);

        if ($service->faqs->isNotEmpty()) {
            $seo['schema'][] = SeoService::faq($service->faqs);
        }

        return view('pages.services.show', compact('service', 'related', 'relatedArticles', 'seo'));
    }
}
