<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'user_id',
    'user_name',
    'action',
    'loggable_type',
    'loggable_id',
    'module',
    'description',
    'changes',
    'ip_address',
    'user_agent',
])]
class ActivityLog extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'changes' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeForAdmin($query)
    {
        return $query->latest();
    }
}
