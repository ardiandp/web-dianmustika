@props(['article'])

<article class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-ink/5 transition hover:-translate-y-1 hover:shadow-lg">
    <a href="{{ route('articles.show', $article) }}" class="relative aspect-[16/10] overflow-hidden">
        @if ($article->featured_image)
            <img src="{{ asset('storage/'.$article->featured_image) }}" alt="{{ $article->alt_text ?: $article->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center bg-brand-100 font-display text-3xl text-brand-400">{{ $article->title[0] ?? 'A' }}</div>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-5">
        @if ($article->category)
            <span class="inline-flex w-fit rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700">{{ $article->category->name }}</span>
        @endif
        <h3 class="mt-3 font-display text-lg font-semibold leading-snug text-brand-800 transition group-hover:text-brand-600">
            <a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a>
        </h3>
        <p class="mt-2 flex-1 text-sm leading-relaxed text-ink/70 line-clamp-3">{{ $article->excerpt }}</p>
        <div class="mt-4 flex items-center justify-between border-t border-ink/5 pt-4 text-xs text-ink/50">
            <span>{{ $article->published_at ? $article->published_at->translatedFormat('d M Y') : '' }}</span>
            <span class="inline-flex items-center gap-1 font-semibold text-gold-600 group-hover:text-gold-700">
                Baca <span aria-hidden="true">→</span>
            </span>
        </div>
    </div>
</article>
