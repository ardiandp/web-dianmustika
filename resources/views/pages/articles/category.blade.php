<x-layouts.app :seo="$seo">

    <x-sections.page-hero :title="$category->name" :description="$category->description" :crumb="$category->name" />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-4">
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @forelse ($articles as $article)
                        <x-cards.article-card :article="$article" />
                    @empty
                        <p class="col-span-full py-12 text-center text-ink/60">Belum ada artikel pada kategori ini.</p>
                    @endforelse
                </div>
            </div>

            <aside>
                <h3 class="font-display text-lg font-semibold text-brand-800">Kategori</h3>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('articles.index') }}" class="flex items-center justify-between rounded-xl bg-white px-4 py-3 text-sm font-medium text-brand-700 ring-1 ring-ink/5 transition hover:bg-brand-50">
                        Semua
                    </a>
                    @foreach ($categories as $item)
                        <a href="{{ route('articles.category', $item) }}" class="flex items-center justify-between rounded-xl bg-white px-4 py-3 text-sm font-medium text-brand-700 ring-1 ring-ink/5 transition hover:bg-brand-50">
                            {{ $item->name }}
                            <span class="rounded-full bg-brand-50 px-2 py-0.5 text-xs text-brand-600">{{ $item->articles_count }}</span>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
        <div class="mt-10 flex justify-center">
            {{ $articles->links('vendor.pagination.public') }}
        </div>
    </section>

    <x-sections.cta />
</x-layouts.app>
