<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ServiceCategory::query()
            ->withCount('services')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.service-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.service-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:service_categories,slug'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category = ServiceCategory::create([
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(ServiceCategory::class, $validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => ServiceCategory::max('sort_order') + 1,
        ]);

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', "Kategori layanan \"{$category->name}\" berhasil dibuat.");
    }

    public function edit(ServiceCategory $category): View
    {
        return view('admin.service-categories.edit', compact('category'));
    }

    public function update(Request $request, ServiceCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:service_categories,slug,'.$category->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(ServiceCategory::class, $validated['name'], $category->id),
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', "Kategori layanan \"{$category->name}\" berhasil diperbarui.");
    }

    public function destroy(ServiceCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', 'Kategori layanan berhasil dihapus.');
    }
}
