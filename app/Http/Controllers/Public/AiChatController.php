<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\AiChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class AiChatController extends Controller
{
    public function chat(Request $request, AiChatService $ai): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session' => ['nullable', 'string', 'max:64'],
            'history' => ['nullable', 'array', 'max:12'],
            'history.*.role' => ['nullable', 'in:user,assistant'],
            'history.*.content' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! $ai->enabled()) {
            return response()->json([
                'reply' => 'Maaf, layanan chat AI sedang dinonaktifkan. Silakan hubungi kami melalui WhatsApp.',
            ]);
        }

        $message = trim((string) $validated['message']);

        if ($message === '') {
            return response()->json(['reply' => 'Pesan tidak boleh kosong.'], 422);
        }

        $token = $validated['session'] ?? null;
        $createdSession = false;

        if ($token && Str::length($token) <= 64) {
            $session = ChatSession::firstOrCreate(
                ['session_token' => $token],
                $this->sessionMeta($request)
            );
        } else {
            $session = ChatSession::create(array_merge(
                ['session_token' => (string) Str::uuid()],
                $this->sessionMeta($request)
            ));
            $createdSession = true;
        }

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'role' => 'user',
            'content' => $message,
        ]);
        $session->increment('message_count');

        $reply = $ai->chat($message, $validated['history'] ?? []);

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'role' => 'assistant',
            'content' => $reply,
        ]);
        $session->increment('message_count');

        $session->update([
            'status' => 'active',
            'last_message_at' => now(),
        ]);

        return response()->json([
            'reply' => $reply,
            'session' => $session->session_token,
        ]);
    }

    private function sessionMeta(Request $request): array
    {
        $ua = $request->userAgent() ?? '';
        $agent = new Agent;
        $agent->setUserAgent($ua);

        $device = 'desktop';
        if ($agent->isTablet()) {
            $device = 'tablet';
        } elseif ($agent->isMobile()) {
            $device = 'mobile';
        }

        return [
            'ip_hash' => hash('sha256', ($request->ip() ?? '0.0.0.0').config('app.key')),
            'device' => $device,
            'browser' => $agent->browser() ?: null,
            'os' => $agent->platform() ?: null,
            'user_agent' => mb_substr($ua, 0, 500),
        ];
    }
}
