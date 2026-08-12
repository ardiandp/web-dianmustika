<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Services\SeoService;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $featured = Article::published()->active()->featured()->ordered()->first();
        $categories = ArticleCategory::active()->ordered()->withCount(['articles' => fn ($q) => $q->published()->active()])->get();

        $articles = Article::published()
            ->active()
            ->with(['category', 'author'])
            ->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
            ->ordered()
            ->paginate(6);

        $seo = SeoService::forPage([
            'title' => 'Artikel',
            'description' => 'Tips perawatan tubuh, kecantikan, dan kesehatan dari Dian Mustika. Artikel informatif untuk membantu Anda merawat diri dengan tepat.',
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Artikel', 'url' => route('articles.index')],
                ]),
            ],
        ]);

        return view('pages.articles.index', compact('featured', 'articles', 'categories', 'seo'));
    }

    public function show(Article $article): View
    {
        abort_unless($article->is_active && $article->published_at && $article->published_at->isPast(), 404);

        $article->load(['category', 'author']);

        $related = Article::published()
            ->active()
            ->where('id', '!=', $article->id)
            ->when($article->article_category_id, fn ($q) => $q->where('article_category_id', $article->article_category_id))
            ->with('category')
            ->ordered()
            ->limit(3)
            ->get();

        $seo = SeoService::for($article, [
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Artikel', 'url' => route('articles.index')],
                    ['label' => $article->category?->name ?? 'Artikel', 'url' => $article->category ? route('articles.category', $article->category) : route('articles.index')],
                    ['label' => $article->title, 'url' => route('articles.show', $article)],
                ]),
                SeoService::article($article),
            ],
        ]);

        return view('pages.articles.show', compact('article', 'related', 'seo'));
    }

    public function category(ArticleCategory $category): View
    {
        abort_unless($category->is_active, 404);

        $categories = ArticleCategory::active()->ordered()->withCount(['articles' => fn ($q) => $q->published()->active()])->get();

        $articles = $category->articles()
            ->published()
            ->active()
            ->with('category')
            ->ordered()
            ->paginate(6);

        $seo = SeoService::for($category, [
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Artikel', 'url' => route('articles.index')],
                    ['label' => $category->name, 'url' => route('articles.category', $category)],
                ]),
            ],
        ]);

        return view('pages.articles.category', compact('category', 'categories', 'articles', 'seo'));
    }
}
