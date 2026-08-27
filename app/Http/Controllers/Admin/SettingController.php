<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\LogsActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    use LogsActivity;
    protected array $fields = [
        'site_name',
        'site_tagline',
        'site_description',
        'logo',
        'favicon',
        'hero_badge',
        'hero_heading',
        'hero_description',
        'hero_image',
        'hero_stat1_value',
        'hero_stat1_label',
        'hero_stat2_value',
        'hero_stat2_label',
        'hero_stat3_value',
        'hero_stat3_label',
        'whatsapp',
        'phone',
        'email',
        'address',
        'opening_hours',
        'social_instagram',
        'social_facebook',
        'social_tiktok',
        'google_maps_embed',
        'footer_copyright',
    ];

    protected array $fileFields = ['logo', 'favicon', 'hero_image'];

    public function edit(): View
    {
        $settings = Setting::pluck('value', 'key');

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:ico,png,svg,webp,jpg,jpeg', 'max:1024'],
            'hero_badge' => ['nullable', 'string', 'max:255'],
            'hero_heading' => ['nullable', 'string', 'max:500'],
            'hero_description' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hero_stat1_value' => ['nullable', 'string', 'max:50'],
            'hero_stat1_label' => ['nullable', 'string', 'max:100'],
            'hero_stat2_value' => ['nullable', 'string', 'max:50'],
            'hero_stat2_label' => ['nullable', 'string', 'max:100'],
            'hero_stat3_value' => ['nullable', 'string', 'max:50'],
            'hero_stat3_label' => ['nullable', 'string', 'max:100'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'opening_hours' => ['nullable', 'string'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_tiktok' => ['nullable', 'url', 'max:255'],
            'google_maps_embed' => ['nullable', 'string'],
            'footer_copyright' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($this->fields as $key) {
            if (in_array($key, $this->fileFields)) {
                if ($request->hasFile($key)) {
                    $oldValue = Setting::where('key', $key)->value('value');

                    if ($oldValue && Storage::disk('public')->exists($oldValue)) {
                        Storage::disk('public')->delete($oldValue);
                    }

                    $path = $request->file($key)->store('settings', 'public');
                    Setting::updateOrCreate(['key' => $key], ['value' => $path]);
                }
                continue;
            }

            $value = $request->input($key);

            if ($key === 'opening_hours' && $value) {
                $value = json_encode($this->parseHours($value));
            }

            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->logActivity('updated', null, "Memperbarui pengaturan website", null, 'settings');

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Pengaturan website berhasil diperbarui.');
    }

    private function parseHours(string $text): array
    {
        $hours = [];

        foreach (explode("\n", $text) as $line) {
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $hours[trim($parts[0])] = trim($parts[1]);
            }
        }

        return $hours;
    }
}
