<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'file_path',
    'file_name',
    'original_name',
    'mime_type',
    'size',
    'width',
    'height',
    'hash',
    'alt_text',
    'caption',
    'thumbnail_path',
    'medium_path',
    'uploaded_by',
])]
class Media extends Model
{
    use HasFactory;

    protected $table = 'media_library';

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function url(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function thumbUrl(): string
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : $this->url();
    }

    public function mediumUrl(): string
    {
        return $this->medium_path ? asset('storage/' . $this->medium_path) : $this->url();
    }

    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    public static function findByHash(string $hash): ?self
    {
        return static::where('hash', $hash)->first();
    }
}
