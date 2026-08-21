<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    protected array $fields = [
        'site_name',
        'site_tagline',
        'site_description',
        'logo',
        'favicon',
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
            if ($key === 'logo' || $key === 'favicon') {
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
