<x-layouts.app :seo="$seo">

    <section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <nav class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-widest text-ink/40" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="transition hover:text-brand-700">Beranda</a>
            <span>/</span>
            <a href="{{ route('articles.index') }}" class="transition hover:text-brand-700">Artikel</a>
            @if ($article->category)
                <span>/</span>
                <a href="{{ route('articles.category', $article->category) }}" class="transition hover:text-brand-700">{{ $article->category->name }}</a>
            @endif
        </nav>

        <h1 class="mt-6 font-display text-3xl font-semibold leading-tight text-brand-800 sm:text-4xl">{{ $article->title }}</h1>

        <div class="mt-5 flex flex-wrap items-center gap-4 text-sm text-ink/50">
            @if ($article->category)
                <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700">{{ $article->category->name }}</span>
            @endif
            <span>{{ $article->published_at->translatedFormat('d M Y') }}</span>
            @if ($article->author)
                <span>·</span>
                <span>{{ $article->author->name }}</span>
            @endif
        </div>

        @if ($article->featured_image)
            <div class="mt-8 overflow-hidden rounded-3xl">
                <img src="{{ asset('storage/'.$article->featured_image) }}" alt="{{ $article->alt_text ?: $article->title }}" class="aspect-[16/9] w-full object-cover">
            </div>
        @endif

        <div class="prose-content mt-10">{{ $article->content }}</div>

        <div class="mt-12 rounded-3xl bg-gradient-to-br from-brand-800 to-brand-900 p-8 text-center">
            <h2 class="font-display text-2xl font-semibold text-cream">Butuh Bantuan Memilih Perawatan?</h2>
            <p class="mx-auto mt-2 max-w-xl text-sm text-brand-100/75">Konsultasikan kebutuhan Anda dengan tim Dian Mustika melalui WhatsApp.</p>
            <a href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya membaca artikel dan ingin konsultasi.') }}" target="_blank" rel="noopener" class="mt-6 inline-flex items-center gap-2 rounded-full bg-[#25D366] px-7 py-3 text-sm font-semibold text-white shadow-lg transition hover:brightness-95">
                Konsultasi via WhatsApp
            </a>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-brand-100 bg-white py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sections.section-heading title="Artikel Terkait" description="Baca juga artikel menarik lainnya.">
                    Baca Juga
                </x-sections.section-heading>
                <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <x-cards.article-card :article="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
