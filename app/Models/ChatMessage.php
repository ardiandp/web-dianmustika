<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'chat_session_id',
    'role',
    'content',
])]
class ChatMessage extends Model
{
    use HasFactory;

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }

    public function scopeUser($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeAssistant($query)
    {
        return $query->where('role', 'assistant');
    }
}
