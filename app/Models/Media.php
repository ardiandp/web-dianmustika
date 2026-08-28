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

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    public function thumbUrl(): string
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        return $this->isImage() ? $this->url() : '';
    }

    public function mediumUrl(): string
    {
        if ($this->medium_path) {
            return asset('storage/' . $this->medium_path);
        }
        return $this->isImage() ? $this->url() : '';
    }

    public function extension(): string
    {
        return strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
    }

    public function fileIcon(): string
    {
        $ext = $this->extension();
        $map = [
            'pdf' => 'far fa-file-pdf text-danger',
            'doc' => 'far fa-file-word text-primary',
            'docx' => 'far fa-file-word text-primary',
            'xls' => 'far fa-file-excel text-success',
            'xlsx' => 'far fa-file-excel text-success',
            'ppt' => 'far fa-file-powerpoint text-warning',
            'pptx' => 'far fa-file-powerpoint text-warning',
            'txt' => 'far fa-file-alt text-muted',
            'rtf' => 'far fa-file-alt text-muted',
            'csv' => 'far fa-file-csv text-success',
            'zip' => 'far fa-file-archive text-secondary',
        ];
        return $map[$ext] ?? 'far fa-file text-muted';
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
