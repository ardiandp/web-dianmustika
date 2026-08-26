<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[Fillable([
    'service_category_id',
    'name',
    'slug',
    'short_description',
    'description',
    'benefits',
    'cocok_untuk',
    'perhatian',
    'duration',
    'price',
    'harga_label',
    'tipe_harga',
    'cta_text',
    'cta_url',
    'video_url',
    'focus_keyword',
    'secondary_keywords',
    'note',
    'image',
    'alt_text',
    'is_featured',
    'is_active',
    'sort_order',
])]
class Service extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'benefits' => 'array',
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class)->withTimestamps();
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)->withTimestamps();
    }

    public function relatedServices(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_related', 'service_id', 'related_service_id')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_service')->withTimestamps();
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ServiceGallery::class)->orderBy('sort_order');
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    /**
     * Display price label with fallback.
     */
    public function displayPrice(): ?string
    {
        if ($this->harga_label) {
            return $this->harga_label;
        }

        if ($this->price !== null) {
            return 'Rp '.number_format((float) $this->price, 0, ',', '.');
        }

        return null;
    }

    /**
     * Simple SEO completeness check.
     * Returns: lengkap | sebagian | belum
     */
    public function seoStatus(): string
    {
        $seo = $this->seo;
        $checks = [
            filled($seo?->title),
            filled($seo?->description),
            filled($this->focus_keyword),
            filled($this->short_description) || filled($this->description),
            filled($this->alt_text),
            filled($this->slug),
        ];

        $score = collect($checks)->filter()->count();

        if ($score >= 5) {
            return 'lengkap';
        }

        if ($score >= 3) {
            return 'sebagian';
        }

        return 'belum';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
