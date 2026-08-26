<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::query()
            ->ordered()
            ->get();

        return view('admin.packages.index', compact('packages'));
    }

    public function create(): View
    {
        return view('admin.packages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:packages,slug'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $package = Package::create([
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(Package::class, $validated['name']),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'promo_price' => $validated['promo_price'] ?? null,
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'image' => $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'packages') : null,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => Package::max('sort_order') + 1,
        ]);

        $this->syncSeo($package, $request->all());

        return redirect()
            ->route('admin.packages.index')
            ->with('success', "Paket \"{$package->name}\" berhasil dibuat.");
    }

    public function edit(Package $package): View
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:packages,slug,'.$package->id],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($package->image);
        }

        $package->update([
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(Package::class, $validated['name'], $package->id),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'promo_price' => $validated['promo_price'] ?? null,
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'image' => $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'packages') : $package->image,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncSeo($package, $request->all());

        return redirect()
            ->route('admin.packages.index')
            ->with('success', "Paket \"{$package->name}\" berhasil diperbarui.");
    }

    public function destroy(Package $package): RedirectResponse
    {
        $this->deleteImage($package->image);
        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
}
