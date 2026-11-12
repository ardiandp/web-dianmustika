<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Location;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class SitemapService
{
    public static function generate(): string
    {
        return Cache::remember('sitemap.xml', now()->addHours(24), function () {
            $staticLastmod = now()->toDateString();

            $urls = collect([
                ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily', 'lastmod' => $staticLastmod],
                ['loc' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => $staticLastmod],
                ['loc' => route('services.index'), 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => $staticLastmod],
                ['loc' => route('packages.index'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => $staticLastmod],
                ['loc' => route('consultation.landing'), 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => $staticLastmod],
                ['loc' => route('galleries.index'), 'priority' => '0.5', 'changefreq' => 'monthly', 'lastmod' => $staticLastmod],
                ['loc' => route('testimonials.index'), 'priority' => '0.6', 'changefreq' => 'weekly', 'lastmod' => $staticLastmod],
                ['loc' => route('articles.index'), 'priority' => '0.9', 'changefreq' => 'daily', 'lastmod' => $staticLastmod],
                ['loc' => route('locations.index'), 'priority' => '0.9', 'changefreq' => 'monthly', 'lastmod' => $staticLastmod],
                ['loc' => route('faqs.index'), 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => $staticLastmod],
                ['loc' => route('contact.index'), 'priority' => '0.7', 'changefreq' => 'yearly', 'lastmod' => $staticLastmod],
            ]);

            $services = Service::active()->ordered()->get()->map(
                fn (Service $service) => [
                    'loc' => route('services.show', $service),
                    'priority' => '0.8',
                    'changefreq' => 'monthly',
                    'lastmod' => $service->updated_at?->toDateString(),
                    'image' => $service->image ? url('/storage/' . ltrim($service->image, '/')) : null,
                    'image_title' => $service->name,
                    'image_caption' => $service->short_description,
                ]
            );

            $packages = Package::active()->ordered()->get()->map(
                fn (Package $package) => [
                    'loc' => route('packages.show', $package),
                    'priority' => '0.8',
                    'changefreq' => 'monthly',
                    'lastmod' => $package->updated_at?->toDateString(),
                    'image' => $package->image ? url('/storage/' . ltrim($package->image, '/')) : null,
                    'image_title' => $package->name,
                    'image_caption' => $package->description,
                ]
            );

            $categories = ArticleCategory::active()->ordered()->get()->map(
                fn (ArticleCategory $category) => [
                    'loc' => route('articles.category', $category),
                    'priority' => '0.6',
                    'changefreq' => 'weekly',
                    'lastmod' => $category->updated_at?->toDateString(),
                ]
            );

            $articles = Article::active()->published()->ordered()->get()->map(
                fn (Article $article) => [
                    'loc' => route('articles.show', $article),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                    'lastmod' => $article->updated_at?->toDateString(),
                    'image' => $article->featured_image ? url('/storage/' . ltrim($article->featured_image, '/')) : null,
                    'image_title' => $article->title,
                    'image_caption' => $article->excerpt,
                ]
            );

            $locations = Location::active()->ordered()->get()->map(
                fn (Location $location) => [
                    'loc' => route('locations.show', $location),
                    'priority' => '0.8',
                    'changefreq' => 'monthly',
                    'lastmod' => $location->updated_at?->toDateString(),
                    'image' => $location->image ? url('/storage/' . ltrim($location->image, '/')) : null,
                    'image_title' => $location->name,
                    'image_caption' => $location->address,
                ]
            );

            $urls = $urls
                ->concat($services)
                ->concat($packages)
                ->concat($categories)
                ->concat($articles)
                ->concat($locations);

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

            foreach ($urls as $url) {
                $xml .= "  <url>\n";
                $xml .= '    <loc>' . e($url['loc']) . "</loc>\n";
                if (! empty($url['lastmod'])) {
                    $xml .= '    <lastmod>' . $url['lastmod'] . "</lastmod>\n";
                }
                if (! empty($url['changefreq'])) {
                    $xml .= '    <changefreq>' . $url['changefreq'] . "</changefreq>\n";
                }
                $xml .= '    <priority>' . e($url['priority']) . "</priority>\n";
                if (! empty($url['image'])) {
                    $xml .= "    <image:image>\n";
                    $xml .= '      <image:loc>' . e($url['image']) . "</image:loc>\n";
                    if (! empty($url['image_title'])) {
                        $xml .= '      <image:title>' . e($url['image_title']) . "</image:title>\n";
                    }
                    if (! empty($url['image_caption'])) {
                        $xml .= '      <image:caption>' . e(str($url['image_caption'])->stripTags()->limit(200)->toString()) . "</image:caption>\n";
                    }
                    $xml .= "    </image:image>\n";
                }
                $xml .= "  </url>\n";
            }

            $xml .= '</urlset>';

            return $xml;
        });
    }

    public static function clear(): void
    {
        Cache::forget('sitemap.xml');
    }
}
