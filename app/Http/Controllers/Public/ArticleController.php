<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
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

        return view('pages.articles.index', compact('featured', 'articles', 'categories'));
    }

    public function show(Article $article): View
    {
        abort_unless($article->is_active && $article->published_at && $article->published_at->isPast(), 404);

        $article->load(['category', 'author']);

        $related = Article::published()
            ->active()
            ->where('id', '!=', $article->id)
            ->when($article->article_category_id, fn ($q) => $q->where('article_category_id', $article->article_category_id))
            ->ordered()
            ->limit(3)
            ->get();

        return view('pages.articles.show', compact('article', 'related'));
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

        return view('pages.articles.category', compact('category', 'categories', 'articles'));
    }
}
