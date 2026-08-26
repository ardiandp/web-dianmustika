<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'path',
    'url',
    'element',
    'label',
    'ip_hash',
    'country',
    'city',
    'device',
    'browser',
    'os',
    'referrer',
    'clicked_at',
])]
class PageClick extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'clicked_at' => 'datetime',
        ];
    }
}
