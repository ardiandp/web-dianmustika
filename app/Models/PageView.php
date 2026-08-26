<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'path',
    'url',
    'title',
    'ip_hash',
    'country',
    'city',
    'device',
    'browser',
    'os',
    'user_agent',
    'referrer',
    'session_id',
    'is_bot',
    'viewed_at',
])]
class PageView extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_bot' => 'boolean',
            'viewed_at' => 'datetime',
        ];
    }
}
