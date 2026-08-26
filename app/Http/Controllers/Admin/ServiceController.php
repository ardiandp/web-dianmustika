<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->with('category')
            ->ordered()
            ->get();

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        $categories = ServiceCategory::active()->ordered()->get();

        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_category_id' => ['nullable', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:services,slug'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $service = Service::create([
            'service_category_id' => $validated['service_category_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(Service::class, $validated['name']),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'benefits' => $request->benefits ? $this->parseLines($request->benefits) : null,
            'duration' => $validated['duration'] ?? null,
            'price' => $validated['price'] ?? null,
            'note' => $validated['note'] ?? null,
            'image' => $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'services') : null,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => Service::max('sort_order') + 1,
        ]);

        $this->syncSeo($service, $request->all());

        return redirect()
            ->route('admin.services.index')
            ->with('success', "Layanan \"{$service->name}\" berhasil dibuat.");
    }

    public function edit(Service $service): View
    {
        $categories = ServiceCategory::active()->ordered()->get();

        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'service_category_id' => ['nullable', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:services,slug,'.$service->id],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($service->image);
        }

        $service->update([
            'service_category_id' => $validated['service_category_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(Service::class, $validated['name'], $service->id),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'benefits' => $request->benefits ? $this->parseLines($request->benefits) : null,
            'duration' => $validated['duration'] ?? null,
            'price' => $validated['price'] ?? null,
            'note' => $validated['note'] ?? null,
            'image' => $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'services') : $service->image,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncSeo($service, $request->all());

        return redirect()
            ->route('admin.services.index')
            ->with('success', "Layanan \"{$service->name}\" berhasil diperbarui.");
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->deleteImage($service->image);
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
