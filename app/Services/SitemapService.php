<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Location;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class SitemapService
{
    public static function generate(): string
    {
        return Cache::remember('sitemap.xml', now()->addHours(24), function () {
            $urls = collect([
                ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
                ['loc' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
                ['loc' => route('services.index'), 'priority' => '0.9', 'changefreq' => 'weekly'],
                ['loc' => route('packages.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
                ['loc' => route('galleries.index'), 'priority' => '0.5', 'changefreq' => 'monthly'],
                ['loc' => route('testimonials.index'), 'priority' => '0.5', 'changefreq' => 'monthly'],
                ['loc' => route('articles.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
                ['loc' => route('locations.index'), 'priority' => '0.9', 'changefreq' => 'monthly'],
                ['loc' => route('faqs.index'), 'priority' => '0.6', 'changefreq' => 'monthly'],
                ['loc' => route('contact.index'), 'priority' => '0.7', 'changefreq' => 'yearly'],
            ]);

            $services = Service::active()->ordered()->get()->map(
                fn (Service $service) => [
                    'loc' => route('services.show', $service),
                    'priority' => '0.8',
                    'changefreq' => 'monthly',
                    'lastmod' => $service->updated_at?->toDateString(),
                ]
            );

            $categories = ArticleCategory::where('is_active', true)->get()->map(
                fn (ArticleCategory $category) => [
                    'loc' => route('articles.category', $category),
                    'priority' => '0.6',
                    'changefreq' => 'weekly',
                ]
            );

            $articles = Article::active()->get()->map(
                fn (Article $article) => [
                    'loc' => route('articles.show', $article),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                    'lastmod' => $article->updated_at?->toDateString(),
                ]
            );

            $locations = Location::active()->ordered()->get()->map(
                fn (Location $location) => [
                    'loc' => route('locations.show', $location),
                    'priority' => '0.8',
                    'changefreq' => 'monthly',
                    'lastmod' => $location->updated_at?->toDateString(),
                ]
            );

            $urls = $urls
                ->concat($services)
                ->concat($categories)
                ->concat($articles)
                ->concat($locations);

            $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

            foreach ($urls as $url) {
                $xml .= "  <url>\n";
                $xml .= '    <loc>'.e($url['loc'])."</loc>\n";
                if (! empty($url['lastmod'])) {
                    $xml .= '    <lastmod>'.$url['lastmod']."</lastmod>\n";
                }
                if (! empty($url['changefreq'])) {
                    $xml .= '    <changefreq>'.$url['changefreq']."</changefreq>\n";
                }
                $xml .= '    <priority>'.e($url['priority'])."</priority>\n";
                $xml .= "  </url>\n";
            }

            $xml .= '</urlset>';

            return $xml;
        });
    }
}
