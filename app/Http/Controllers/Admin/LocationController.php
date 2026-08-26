<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::query()
            ->ordered()
            ->get();

        return view('admin.locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('admin.locations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:locations,slug'],
            'address' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'google_maps_url' => ['nullable', 'url', 'max:255'],
            'google_maps_embed' => ['nullable', 'string'],
            'opening_hours' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $location = Location::create([
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(Location::class, $validated['name']),
            'address' => $validated['address'],
            'description' => $validated['description'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'email' => $validated['email'] ?? null,
            'google_maps_url' => $validated['google_maps_url'] ?? null,
            'google_maps_embed' => $validated['google_maps_embed'] ?? null,
            'opening_hours' => $request->opening_hours ? $this->parseHours($request->opening_hours) : null,
            'image' => $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'locations') : null,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => Location::max('sort_order') + 1,
        ]);

        $this->syncSeo($location, $request->all());

        return redirect()
            ->route('admin.locations.index')
            ->with('success', "Lokasi \"{$location->name}\" berhasil dibuat.");
    }

    public function edit(Location $location): View
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:locations,slug,'.$location->id],
            'address' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'google_maps_url' => ['nullable', 'url', 'max:255'],
            'google_maps_embed' => ['nullable', 'string'],
            'opening_hours' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($location->image);
        }

        $location->update([
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(Location::class, $validated['name'], $location->id),
            'address' => $validated['address'],
            'description' => $validated['description'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'email' => $validated['email'] ?? null,
            'google_maps_url' => $validated['google_maps_url'] ?? null,
            'google_maps_embed' => $validated['google_maps_embed'] ?? null,
            'opening_hours' => $request->opening_hours ? $this->parseHours($request->opening_hours) : null,
            'image' => $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'locations') : $location->image,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncSeo($location, $request->all());

        return redirect()
            ->route('admin.locations.index')
            ->with('success', "Lokasi \"{$location->name}\" berhasil diperbarui.");
    }

    public function destroy(Location $location): RedirectResponse
    {
        $this->deleteImage($location->image);
        $location->delete();

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Lokasi berhasil dihapus.');
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
