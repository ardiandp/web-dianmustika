<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Static memo (Setting::$resolved) bocor antar-test dalam satu proses.
        $property = new \ReflectionProperty(Setting::class, 'resolved');
        $property->setValue(null, null);

        Permission::findOrCreate('manage-settings');
    }

    public function test_knowledge_source_checkboxes_reflect_saved_setting(): void
    {
        $user = User::factory()->create()->givePermissionTo('manage-settings');
        Setting::updateOrCreate(['key' => 'chat_ai_sources'], ['value' => json_encode(['faq', 'lokasi'])]);

        $this->actingAs($user)
            ->get('/admin/settings')
            ->assertOk()
            ->assertSee('value="faq" id="source_faq" checked', false)
            ->assertSee('value="lokasi" id="source_lokasi" checked', false)
            ->assertDontSee('value="layanan" id="source_layanan" checked', false)
            ->assertDontSee('value="paket" id="source_paket" checked', false)
            ->assertDontSee('value="artikel" id="source_artikel" checked', false);
    }
}
