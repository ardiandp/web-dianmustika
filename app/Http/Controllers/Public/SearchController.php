<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Faq;
use App\Models\Location;
use App\Models\Package;
use App\Models\Service;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $results = collect();
        $counts = ['services' => 0, 'packages' => 0, 'articles' => 0, 'locations' => 0, 'faqs' => 0];

        if (mb_strlen($q) >= 2) {
            $like = '%' . $q . '%';

            $services = Service::active()
                ->where(function ($w) use ($like) {
                    $w->where('name', 'like', $like)
                        ->orWhere('short_description', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('benefits', 'like', $like)
                        ->orWhere('cocok_untuk', 'like', $like);
                })
                ->with('category')
                ->ordered()
                ->limit(12)
                ->get()
                ->map(fn ($m) => [
                    'type' => 'Layanan',
                    'type_slug' => 'layanan',
                    'title' => $m->name,
                    'excerpt' => $m->short_description ?: str($m->description ?? '')->stripTags()->limit(120)->toString(),
                    'url' => route('services.show', $m),
                    'image' => $m->image,
                    'badge' => $m->category?->name,
                ]);
            $counts['services'] = $services->count();

            $packages = Package::active()
                ->where(function ($w) use ($like) {
                    $w->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like);
                })
                ->ordered()
                ->limit(12)
                ->get()
                ->map(fn ($m) => [
                    'type' => 'Paket',
                    'type_slug' => 'paket',
                    'title' => $m->name,
                    'excerpt' => str($m->description ?? '')->stripTags()->limit(120)->toString(),
                    'url' => route('packages.index') . '#paket-' . $m->slug,
                    'image' => $m->image,
                    'badge' => $m->promo_price ? 'Promo' : null,
                ]);
            $counts['packages'] = $packages->count();

            $articles = Article::published()->active()
                ->where(function ($w) use ($like) {
                    $w->where('title', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('content', 'like', $like);
                })
                ->with('category')
                ->ordered()
                ->limit(12)
                ->get()
                ->map(fn ($m) => [
                    'type' => 'Artikel',
                    'type_slug' => 'artikel',
                    'title' => $m->title,
                    'excerpt' => $m->excerpt ?: str($m->content ?? '')->stripTags()->limit(120)->toString(),
                    'url' => route('articles.show', $m),
                    'image' => $m->featured_image,
                    'badge' => $m->category?->name,
                ]);
            $counts['articles'] = $articles->count();

            $locations = Location::active()
                ->where(function ($w) use ($like) {
                    $w->where('name', 'like', $like)
                        ->orWhere('address', 'like', $like)
                        ->orWhere('description', 'like', $like);
                })
                ->ordered()
                ->limit(12)
                ->get()
                ->map(fn ($m) => [
                    'type' => 'Lokasi',
                    'type_slug' => 'lokasi',
                    'title' => $m->name,
                    'excerpt' => $m->address,
                    'url' => route('locations.show', $m),
                    'image' => $m->image,
                    'badge' => null,
                ]);
            $counts['locations'] = $locations->count();

            $faqs = Faq::active()
                ->where(function ($w) use ($like) {
                    $w->where('question', 'like', $like)
                        ->orWhere('answer', 'like', $like);
                })
                ->limit(8)
                ->get()
                ->map(fn ($m) => [
                    'type' => 'FAQ',
                    'type_slug' => 'faq',
                    'title' => $m->question,
                    'excerpt' => str($m->answer ?? '')->stripTags()->limit(120)->toString(),
                    'url' => route('faqs.index') . '#faq-' . $m->id,
                    'image' => null,
                    'badge' => ucfirst($m->category ?? 'Umum'),
                ]);
            $counts['faqs'] = $faqs->count();

            // Mixed list: interleave by relevance (services first, then articles, packages, locations, faqs)
            $results = collect()
                ->concat($services)
                ->concat($articles)
                ->concat($packages)
                ->concat($locations)
                ->concat($faqs)
                ->take(30);
        }

        $total = array_sum($counts);

        $seo = SeoService::forPage([
            'title' => $q !== '' ? 'Pencarian "' . $q . '"' : 'Pencarian',
            'description' => $q !== '' ? 'Hasil pencarian untuk "' . $q . '" di Dian Mustika.' : 'Cari layanan, paket, artikel, dan lokasi di Dian Mustika.',
            'robots' => 'noindex, follow',
        ]);

        return view('pages.search.index', compact('q', 'results', 'counts', 'total', 'seo'));
    }

    public function suggest(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $like = '%' . $q . '%';
        $items = collect();

        $services = Service::active()
            ->where('name', 'like', $like)
            ->orWhere('short_description', 'like', $like)
            ->limit(4)->get(['name', 'slug', 'short_description'])->map(fn ($m) => [
                'type' => 'Layanan',
                'title' => $m->name,
                'url' => route('services.show', $m->slug),
            ]);

        $articles = Article::published()->active()
            ->where('title', 'like', $like)
            ->limit(3)->get(['title', 'slug'])->map(fn ($m) => [
                'type' => 'Artikel',
                'title' => $m->title,
                'url' => route('articles.show', $m->slug),
            ]);

        $packages = Package::active()
            ->where('name', 'like', $like)
            ->limit(2)->get(['name', 'slug'])->map(fn ($m) => [
                'type' => 'Paket',
                'title' => $m->name,
                'url' => route('packages.index') . '#paket-' . $m->slug,
            ]);

        $locations = Location::active()
            ->where('name', 'like', $like)
            ->limit(2)->get(['name', 'slug'])->map(fn ($m) => [
                'type' => 'Lokasi',
                'title' => $m->name,
                'url' => route('locations.show', $m->slug),
            ]);

        $items = $items->concat($services)->concat($articles)->concat($packages)->concat($locations)->take(8)->values();

        return response()->json($items);
    }
}
