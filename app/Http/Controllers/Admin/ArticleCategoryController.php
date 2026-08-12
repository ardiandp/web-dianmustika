<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ArticleCategory::query()
            ->withCount('articles')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('admin.article-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.article-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:article_categories,slug'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category = ArticleCategory::create([
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(ArticleCategory::class, $validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => ArticleCategory::max('sort_order') + 1,
        ]);

        return redirect()
            ->route('admin.article-categories.index')
            ->with('success', "Kategori artikel \"{$category->name}\" berhasil dibuat.");
    }

    public function edit(ArticleCategory $category): View
    {
        return view('admin.article-categories.edit', compact('category'));
    }

    public function update(Request $request, ArticleCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:article_categories,slug,'.$category->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(ArticleCategory::class, $validated['name'], $category->id),
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.article-categories.index')
            ->with('success', "Kategori artikel \"{$category->name}\" berhasil diperbarui.");
    }

    public function destroy(ArticleCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.article-categories.index')
            ->with('success', 'Kategori artikel berhasil dihapus.');
    }
}
