<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'title',
    'description',
    'keywords',
    'canonical',
    'robots',
    'og_title',
    'og_description',
    'og_image',
    'schema',
])]
class SeoMetadata extends Model
{
    use HasFactory;

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
