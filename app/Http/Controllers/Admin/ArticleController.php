<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()
            ->with(['category', 'author'])
            ->ordered()
            ->get();

        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        $categories = ArticleCategory::active()->ordered()->get();
        $authors = User::orderBy('name')->get();

        return view('admin.articles.create', compact('categories', 'authors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'article_category_id' => ['nullable', 'exists:article_categories,id'],
            'author_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $article = Article::create([
            'article_category_id' => $validated['article_category_id'] ?? null,
            'author_id' => $validated['author_id'],
            'title' => $validated['title'],
            'slug' => $request->slug ?: $this->makeSlug(Article::class, $validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'featured_image' => $request->hasFile('featured_image') ? $this->uploadImage($request->file('featured_image'), 'articles') : null,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'published_at' => $request->published_at ?: now(),
        ]);

        $this->syncSeo($article, $request->all());

        return redirect()
            ->route('admin.articles.index')
            ->with('success', "Artikel \"{$article->title}\" berhasil dibuat.");
    }

    public function edit(Article $article): View
    {
        $categories = ArticleCategory::active()->ordered()->get();
        $authors = User::orderBy('name')->get();

        return view('admin.articles.edit', compact('article', 'categories', 'authors'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'article_category_id' => ['nullable', 'exists:article_categories,id'],
            'author_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug,'.$article->id],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('featured_image')) {
            $this->deleteImage($article->featured_image);
        }

        $article->update([
            'article_category_id' => $validated['article_category_id'] ?? null,
            'author_id' => $validated['author_id'],
            'title' => $validated['title'],
            'slug' => $request->slug ?: $this->makeSlug(Article::class, $validated['title'], $article->id),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'featured_image' => $request->hasFile('featured_image') ? $this->uploadImage($request->file('featured_image'), 'articles') : $article->featured_image,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'published_at' => $request->published_at ?: $article->published_at,
        ]);

        $this->syncSeo($article, $request->all());

        return redirect()
            ->route('admin.articles.index')
            ->with('success', "Artikel \"{$article->title}\" berhasil diperbarui.");
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->deleteImage($article->featured_image);
        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}
