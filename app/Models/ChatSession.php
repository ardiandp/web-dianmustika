<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'session_token',
    'ip_hash',
    'country',
    'city',
    'device',
    'browser',
    'os',
    'user_agent',
    'message_count',
    'status',
    'last_message_at',
])]
class ChatSession extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Aktif',
            'closed' => 'Ditutup',
            default => (string) $this->status,
        };
    }
}
