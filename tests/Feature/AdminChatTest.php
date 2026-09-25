<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdminChatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::findOrCreate('manage-chat');
    }

    public function test_admin_can_view_chat_session_detail(): void
    {
        $user = User::factory()->create()->givePermissionTo('manage-chat');
        $session = ChatSession::create(['session_token' => 'reg-view']);
        ChatMessage::create(['chat_session_id' => $session->id, 'role' => 'user', 'content' => 'Halo, minta info paket.']);

        $this->actingAs($user)
            ->get('/admin/chats/'.$session->id)
            ->assertOk()
            ->assertSee('Detail Sesi Chat')
            ->assertSee('Halo, minta info paket.');
    }

    public function test_admin_can_delete_chat_session(): void
    {
        $user = User::factory()->create()->givePermissionTo('manage-chat');
        $session = ChatSession::create(['session_token' => 'reg-del']);
        ChatMessage::create(['chat_session_id' => $session->id, 'role' => 'user', 'content' => 'Halo.']);

        $this->actingAs($user)
            ->delete('/admin/chats/'.$session->id)
            ->assertRedirect(route('admin.chats.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('chat_sessions', ['id' => $session->id]);
        $this->assertDatabaseCount('chat_messages', 0);
    }
}
