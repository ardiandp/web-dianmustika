<x-layouts.app title="Artikel" description="Tips dan informasi seputar perawatan tubuh dan kecantikan.">

    <x-sections.page-hero title="Artikel & Tips" description="Informasi dan tips seputar perawatan tubuh dan kecantikan." />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        @if ($featured)
            <a href="{{ route('articles.show', $featured) }}" class="group grid grid-cols-1 overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-ink/5 transition hover:shadow-lg lg:grid-cols-2">
                <div class="relative aspect-[16/10] overflow-hidden lg:aspect-auto">
                    @if ($featured->featured_image)
                        <img src="{{ asset('storage/'.$featured->featured_image) }}" alt="{{ $featured->alt_text ?: $featured->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-brand-100 font-display text-5xl text-brand-400">{{ $featured->title[0] ?? 'A' }}</div>
                    @endif
                    <span class="absolute left-4 top-4 rounded-full bg-gold-500 px-3 py-1 text-xs font-bold uppercase tracking-wide text-brand-950">Artikel Pilihan</span>
                </div>
                <div class="flex flex-col justify-center p-8 lg:p-10">
                    @if ($featured->category)
                        <span class="inline-flex w-fit rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700">{{ $featured->category->name }}</span>
                    @endif
                    <h2 class="mt-4 font-display text-2xl font-semibold leading-snug text-brand-800 transition group-hover:text-brand-600 sm:text-3xl">{{ $featured->title }}</h2>
                    <p class="mt-3 leading-relaxed text-ink/70">{{ $featured->excerpt }}</p>
                    <div class="mt-5 flex items-center gap-4 text-xs text-ink/50">
                        <span>{{ $featured->published_at->translatedFormat('d M Y') }}</span>
                        @if ($featured->author)
                            <span>·</span>
                            <span>{{ $featured->author->name }}</span>
                        @endif
                    </div>
                    <span class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-gold-600">
                        Baca Selengkapnya <span aria-hidden="true">→</span>
                    </span>
                </div>
            </a>
        @endif

        <div class="mt-14 grid grid-cols-1 gap-10 lg:grid-cols-4">
            <div class="lg:col-span-3">
                <h2 class="font-display text-2xl font-semibold text-brand-800">Artikel Terbaru</h2>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @forelse ($articles as $article)
                        <x-cards.article-card :article="$article" />
                    @empty
                        <p class="col-span-full py-12 text-center text-ink/60">Belum ada artikel.</p>
                    @endforelse
                </div>
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            </div>

            <aside>
                <h3 class="font-display text-lg font-semibold text-brand-800">Kategori</h3>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('articles.index') }}" class="flex items-center justify-between rounded-xl bg-white px-4 py-3 text-sm font-medium text-brand-700 ring-1 ring-ink/5 transition hover:bg-brand-50">
                        Semua
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('articles.category', $category) }}" class="flex items-center justify-between rounded-xl bg-white px-4 py-3 text-sm font-medium text-brand-700 ring-1 ring-ink/5 transition hover:bg-brand-50">
                            {{ $category->name }}
                            <span class="rounded-full bg-brand-50 px-2 py-0.5 text-xs text-brand-600">{{ $category->articles_count }}</span>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </section>

    <x-sections.cta />
</x-layouts.app>
