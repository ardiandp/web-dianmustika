<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Faq;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiChatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Static memo (Setting::$resolved) hidup dalam satu proses PHP dan bocor antar-test.
        $property = new \ReflectionProperty(Setting::class, 'resolved');
        $property->setValue(null, null);
    }

    public function test_chat_endpoint_stores_messages_and_returns_reply(): void
    {
        Http::fake([
            'localhost:11434/*' => Http::response([
                'message' => ['role' => 'assistant', 'content' => 'Tentu, berikut promo yang sedang berjalan di Dian Mustika.'],
            ], 200),
        ]);

        $response = $this->postJson('/api/chat', [
            'message' => 'Promo apa yang sedang berjalan?',
            'session' => 'test-session',
        ]);

        $response->assertOk()
            ->assertJson([
                'reply' => 'Tentu, berikut promo yang sedang berjalan di Dian Mustika.',
                'session' => 'test-session',
            ]);

        $this->assertDatabaseHas('chat_sessions', ['session_token' => 'test-session']);
        $this->assertDatabaseHas('chat_messages', ['role' => 'user', 'content' => 'Promo apa yang sedang berjalan?']);
        $this->assertDatabaseHas('chat_messages', ['role' => 'assistant', 'content' => 'Tentu, berikut promo yang sedang berjalan di Dian Mustika.']);

        $session = ChatSession::where('session_token', 'test-session')->first();
        $this->assertEquals(2, $session->message_count);
        $this->assertNotNull($session->last_message_at);
    }

    public function test_chat_reuses_existing_session(): void
    {
        Http::fake([
            'localhost:11434/*' => Http::response(['message' => ['content' => 'Halo!']], 200),
        ]);

        $session = ChatSession::create(['session_token' => 'abc-123']);

        $this->postJson('/api/chat', ['message' => 'Halo', 'session' => 'abc-123'])->assertOk();

        $this->assertSame(2, $session->fresh()->message_count);
        $this->assertSame(1, ChatSession::count());
    }

    public function test_chat_returns_friendly_fallback_when_ollama_down(): void
    {
        Http::fake([
            'localhost:11434/*' => Http::response('', 503),
        ]);

        $response = $this->postJson('/api/chat', ['message' => 'Halo']);

        $response->assertOk();
        $this->assertStringContainsString('WhatsApp', $response->json('reply'));

        $this->assertDatabaseHas('chat_messages', ['role' => 'assistant']);
    }

    public function test_chat_disabled_returns_notice_without_call(): void
    {
        Setting::updateOrCreate(['key' => 'chat_ai_enabled'], ['value' => '0']);

        Http::fake();

        $response = $this->postJson('/api/chat', ['message' => 'Halo']);

        $response->assertOk()
            ->assertJsonPath('reply', 'Maaf, layanan chat AI sedang dinonaktifkan. Silakan hubungi kami melalui WhatsApp.');

        Http::assertNothingSent();
        $this->assertSame(0, ChatMessage::count());
    }

    public function test_chat_rejects_empty_message(): void
    {
        Http::fake();

        $this->postJson('/api/chat', ['message' => ''])->assertStatus(422);

        Http::assertNothingSent();
    }

    public function test_artikel_terbit_masuk_ke_knowledge_saat_diaktifkan(): void
    {
        Http::fake([
            'localhost:11434/*' => Http::response(['message' => ['content' => 'Artikel tersedia.']], 200),
        ]);

        Article::factory()->create([
            'title' => 'Tips Mengatasi Perut Kembung',
            'excerpt' => 'Perut kembung setelah melahirkan bisa diredakan dengan pijat lembut.',
            'is_active' => true,
            'published_at' => now(),
        ]);

        $this->postJson('/api/chat', ['message' => 'Tips perut kembung?'])->assertOk();

        Http::assertSent(function ($request) {
            $system = collect($request->data()['messages'])->firstWhere('role', 'system')['content'] ?? '';

            return str_contains($system, 'ARTIKEL — Tips Mengatasi Perut Kembung')
                && str_contains($system, 'Perut kembung setelah melahirkan');
        });
    }

    public function test_sumber_yang_dinonaktifkan_tidak_masuk_prompt(): void
    {
        Http::fake([
            'localhost:11434/*' => Http::response(['message' => ['content' => 'Ok.']], 200),
        ]);

        Service::factory()->create(['name' => 'Massage Kaki', 'is_active' => true]);
        Faq::factory()->create(['question' => 'Apakah buka hari Minggu?', 'is_active' => true]);

        Setting::updateOrCreate(['key' => 'chat_ai_sources'], ['value' => json_encode(['faq'])]);

        $this->postJson('/api/chat', ['message' => 'Massage kaki?'])->assertOk();

        Http::assertSent(function ($request) {
            $system = collect($request->data()['messages'])->firstWhere('role', 'system')['content'] ?? '';

            return str_contains($system, 'Apakah buka hari Minggu?')
                && ! str_contains($system, 'Massage Kaki');
        });
    }
}
