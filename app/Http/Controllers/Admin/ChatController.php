<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Traits\LogsActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    use LogsActivity;

    public function index(Request $request): View
    {
        $query = ChatSession::query()->withCount('messages');

        if ($request->filled('search')) {
            $like = '%'.$request->search.'%';
            $query->where(function ($q) use ($like) {
                $q->where('session_token', 'like', $like)
                    ->orWhereHas('messages', fn ($m) => $m->where('content', 'like', $like));
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('last_message_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('last_message_at', '<=', $request->date_to);
        }

        $sessions = $query
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.chats.index', compact('sessions'));
    }

    public function show(ChatSession $chat): View
    {
        $chat->load(['messages']);

        return view('admin.chats.show', compact('chat'));
    }

    public function destroy(ChatSession $chat): RedirectResponse
    {
        $this->logActivity('deleted', $chat, "Menghapus sesi chat AI #{$chat->id}");

        $chat->delete();

        return redirect()
            ->route('admin.chats.index')
            ->with('success', 'Sesi chat berhasil dihapus.');
    }
}
