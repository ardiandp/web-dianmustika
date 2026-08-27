<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Location;
use App\Models\Package;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SeoService
{
    /**
     * Build SEO metadata for a static page (no related model).
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function forPage(array $overrides = []): array
    {
        return self::build(null, $overrides);
    }

    /**
     * Build SEO metadata for a model-backed page with fallback chain:
     * specific override -> SeoMetadata row -> generated -> site default.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function for(?Model $model, array $overrides = []): array
    {
        return self::build($model, $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    protected static function build(?Model $model, array $overrides): array
    {
        $siteName = Setting::get('site_name', config('app.name'));
        $seo = $model?->seo;

        $title = $overrides['title']
            ?? $seo?->title
            ?? self::generateTitle($model, $siteName)
            ?? Setting::get('default_meta_title')
            ?? $siteName;

        if ($title && ! str_contains($title, $siteName)) {
            $title .= ' | '.$siteName;
        }

        $description = $overrides['description']
            ?? $seo?->description
            ?? self::generateDescription($model)
            ?? Setting::get('default_meta_description')
            ?? Setting::get('site_description', '');

        $canonical = $overrides['canonical']
            ?? $seo?->canonical
            ?? request()->url();

        $ogImage = $overrides['og_image']
            ?? $seo?->og_image
            ?? self::generateImage($model)
            ?? self::absoluteImage(Setting::get('default_og_image'));

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $overrides['robots'] ?? $seo?->robots ?? 'index, follow',
            'keywords' => $overrides['keywords'] ?? $seo?->keywords ?? '',
            'type' => $overrides['type'] ?? 'website',
            'og' => [
                'site_name' => $siteName,
                'locale' => 'id_ID',
                'type' => $overrides['og_type'] ?? ($model instanceof Article ? 'article' : ($model instanceof Service ? 'product' : 'website')),
                'title' => $overrides['og_title'] ?? $seo?->og_title ?? $title,
                'description' => $overrides['og_description'] ?? $seo?->og_description ?? $description,
                'image' => $ogImage,
                'url' => $canonical,
            ],
            'schema' => self::mergeSchema($model, $overrides['schema'] ?? []),
        ];
    }

    protected static function generateTitle(?Model $model, string $siteName): ?string
    {
        if (! $model) {
            return null;
        }

        $name = match (true) {
            $model instanceof Article => $model->title,
            $model instanceof Service => $model->name,
            $model instanceof Package => $model->name,
            $model instanceof Location => $model->name,
            default => $model->name ?? $model->title ?? null,
        };

        if (! $name) {
            return null;
        }

        return $name.' | '.$siteName;
    }

    protected static function generateDescription(?Model $model): ?string
    {
        if (! $model) {
            return null;
        }

        $text = match (true) {
            $model instanceof Article => $model->excerpt,
            $model instanceof Service => $model->short_description,
            $model instanceof Package => $model->description,
            $model instanceof Location => $model->description,
            default => $model->description ?? $model->excerpt ?? null,
        };

        if (! $text) {
            return null;
        }

        return str($text)->replaceMatches('/\s+/', ' ')->limit(160)->toString();
    }

    protected static function generateImage(?Model $model): ?string
    {
        $path = $model->featured_image ?? $model->image ?? null;

        return $path ? self::absoluteImage($path) : null;
    }

    public static function absoluteImage(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return request()->root().'/storage/'.ltrim($path, '/');
    }

    /**
     * @param  array<int, mixed>  $generated
     * @return array<int, mixed>
     */
    protected static function mergeSchema(?Model $model, array $generated): array
    {
        $schema = $generated;

        $stored = $model?->seo?->schema;
        if ($stored) {
            $decoded = is_string($stored) ? json_decode($stored, true) : $stored;
            if (is_array($decoded)) {
                $schema = array_merge($schema, array_values($decoded));
            }
        }

        return array_values($schema);
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $items
     * @return array<string, mixed>
     */
    public static function breadcrumbs(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn ($item, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['label'],
                'item' => $item['url'],
            ])->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function organization(): array
    {
        $name = Setting::get('site_name', config('app.name'));

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => url('/'),
            'description' => Setting::get('site_description', ''),
            'email' => Setting::get('email'),
            'telephone' => Setting::get('phone'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'ID',
                'addressLocality' => 'Tangerang Selatan',
                'streetAddress' => Setting::get('address', ''),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function webSite(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => Setting::get('site_name', config('app.name')),
            'url' => url('/'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function article(Article $article): array
    {
        $image = self::generateImage($article);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $article->excerpt,
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
            'datePublished' => optional($article->published_at)->toIso8601String(),
            'dateModified' => optional($article->updated_at)->toIso8601String(),
            'author' => ['@type' => 'Organization', 'name' => Setting::get('site_name', config('app.name'))],
            'publisher' => [
                '@type' => 'Organization',
                'name' => Setting::get('site_name', config('app.name')),
                'logo' => ['@type' => 'ImageObject', 'url' => self::absoluteImage(Setting::get('logo'))],
            ],
            'inLanguage' => 'id-ID',
        ];

        if ($image) {
            $schema['image'] = $image;
        }

        return $schema;
    }

    /**
     * @return array<string, mixed>
     */
    public static function service(Service $service): array
    {
        $description = $service->short_description ?: str($service->description ?? '')->stripTags()->limit(300)->toString();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service->name,
            'description' => $description,
            'provider' => ['@type' => 'Organization', 'name' => Setting::get('site_name', config('app.name'))],
            'url' => url()->current(),
        ];

        // areaServed from related locations, fallback to default
        if ($service->relationLoaded('locations') && $service->locations->isNotEmpty()) {
            $schema['areaServed'] = $service->locations->map(fn ($loc) => [
                '@type' => 'City',
                'name' => $loc->name,
            ])->values()->all();
            // Keep single object if only one location for backward compat
            if (count($schema['areaServed']) === 1) {
                $schema['areaServed'] = $schema['areaServed'][0];
            }
        } else {
            $schema['areaServed'] = ['@type' => 'City', 'name' => 'Tangerang Selatan'];
        }

        if ($image = self::generateImage($service)) {
            $schema['image'] = $image;
        }

        // Gallery images as additional images
        if ($service->relationLoaded('galleries') && $service->galleries->isNotEmpty()) {
            $galleryImages = $service->galleries->map(fn ($g) => self::absoluteImage($g->image))->filter()->values()->all();
            if (! empty($galleryImages)) {
                $schema['image'] = array_values(array_unique(array_merge(
                    is_array($schema['image'] ?? null) ? $schema['image'] : [$schema['image'] ?? null],
                    $galleryImages
                )));
                $schema['image'] = array_values(array_filter($schema['image']));
                if (count($schema['image']) === 1) {
                    $schema['image'] = $schema['image'][0];
                }
            }
        }

        if ($service->price && $service->price > 0) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => (string) $service->price,
                'priceCurrency' => 'IDR',
                'availability' => 'https://schema.org/InStock',
                'url' => url()->current(),
            ];
        } elseif ($service->harga_label) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'priceCurrency' => 'IDR',
                'availability' => 'https://schema.org/InStock',
                'url' => url()->current(),
            ];
        }

        return $schema;
    }

    /**
     * @return array<string, mixed>
     */
    public static function package(Package $package): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $package->name,
            'description' => str($package->description ?? '')->stripTags()->limit(300)->toString(),
            'brand' => ['@type' => 'Brand', 'name' => Setting::get('site_name', config('app.name'))],
            'url' => url()->current(),
        ];

        if ($image = self::generateImage($package)) {
            $schema['image'] = $image;
        }

        $price = $package->hasPromo() ? $package->promo_price : $package->price;
        if ($price && $price > 0) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => (string) $price,
                'priceCurrency' => 'IDR',
                'availability' => 'https://schema.org/InStock',
                'url' => url()->current(),
                'priceValidUntil' => $package->ends_at?->toIso8601String(),
            ];
        }

        return $schema;
    }

    /**
     * @param  Collection<int, Faq>  $faqs
     * @return array<string, mixed>
     */
    public static function faq(Collection $faqs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->values()->map(fn (Faq $faq) => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => str($faq->answer)->limit(500)->toString(),
                ],
            ])->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function beautySalon(Location $location): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BeautySalon',
            'name' => $location->name,
            'description' => $location->description,
            'url' => url()->current(),
            'telephone' => $location->phone ?: $location->whatsapp,
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'ID',
                'streetAddress' => $location->address,
            ],
            'image' => self::generateImage($location),
        ];

        if ($location->google_maps_url) {
            $schema['hasMap'] = $location->google_maps_url;
        }

        if ($hours = self::openingHours($location->opening_hours)) {
            $schema['openingHours'] = $hours;
        }

        if ($priceRange = Setting::get('price_range')) {
            $schema['priceRange'] = $priceRange;
        }

        return $schema;
    }

    /**
     * Convert Indonesian opening hours map to schema.org day ranges.
     *
     * @return array<int, string>
     */
    protected static function openingHours(?array $hours): array
    {
        if (! $hours) {
            return [];
        }

        $days = [
            'Senin - Jumat' => 'Mo-Fr',
            'Sabtu' => 'Sa',
            'Minggu' => 'Su',
            'Sabtu - Minggu' => 'Sa-Su',
            'Setiap Hari' => 'Mo-Su',
            'Senin' => 'Mo',
            'Selasa' => 'Tu',
            'Rabu' => 'We',
            'Kamis' => 'Th',
            'Jumat' => 'Fr',
        ];

        $result = [];
        foreach ($hours as $range => $time) {
            $key = trim($range);
            $day = $days[$key] ?? null;
            if (! $day || ! $time) {
                continue;
            }

            $time = preg_replace('/\s+/', '', $time);
            $time = str_replace('.', ':', $time);
            $result[] = $day.' '.$time;
        }

        return $result;
    }
}
