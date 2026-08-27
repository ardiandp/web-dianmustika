<x-layouts.app :seo="$seo">
    <section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- Header --}}
        <nav class="mb-6 flex items-center gap-2 text-xs uppercase tracking-widest text-ink/40" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="transition hover:text-brand-700">Beranda</a>
            <span>/</span>
            <span class="text-brand-700">Pencarian</span>
        </nav>

        <h1 class="font-display text-3xl font-semibold text-brand-800">Pencarian</h1>
        <p class="mt-2 text-sm text-ink/60">Temukan layanan, paket, artikel, lokasi, dan FAQ Dian Mustika.</p>

        {{-- Search input --}}
        <form method="GET" action="{{ route('search.index') }}" class="mt-6">
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-ink/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z"/></svg>
                </span>
                <input
                    type="search"
                    name="q"
                    value="{{ $q }}"
                    placeholder="Cari layanan, paket, artikel, lokasi..."
                    autofocus
                    class="w-full rounded-full border border-brand-200 bg-white py-3.5 pl-12 pr-28 text-sm text-ink placeholder:text-ink/40 focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                >
                <button type="submit" class="absolute inset-y-1.5 right-1.5 rounded-full bg-brand-700 px-6 text-sm font-semibold text-white transition hover:bg-brand-800">Cari</button>
            </div>
        </form>

        @if ($q !== '')
            <p class="mt-4 text-sm text-ink/60">
                Menampilkan <span class="font-semibold text-brand-800">{{ $total }}</span> hasil untuk
                <span class="font-semibold text-brand-800">"{{ $q }}"</span>
                @if ($total > 0)
                    — <span class="text-xs">Layanan {{ $counts['services'] }} · Paket {{ $counts['packages'] }} · Artikel {{ $counts['articles'] }} · Lokasi {{ $counts['locations'] }} · FAQ {{ $counts['faqs'] }}</span>
                @endif
            </p>
        @endif

        {{-- Results mixed list --}}
        @if ($q !== '' && $total === 0)
            <div class="mt-10 rounded-3xl border border-dashed border-brand-200 bg-white p-10 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z"/></svg>
                </div>
                <h2 class="mt-4 font-display text-lg font-semibold text-brand-800">Tidak ada hasil</h2>
                <p class="mt-2 text-sm text-ink/60">Coba kata kunci lain seperti <span class="font-medium">pijat, lulur, promo, perawatan</span>.</p>
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    <a href="{{ route('services.index') }}" class="rounded-full bg-brand-700 px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-800">Lihat Layanan</a>
                    <a href="{{ route('articles.index') }}" class="rounded-full border border-brand-200 bg-white px-5 py-2 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">Lihat Artikel</a>
                </div>
            </div>
        @elseif ($q !== '' && $total > 0)
            <div class="mt-8 space-y-4">
                @foreach ($results as $item)
                    <a href="{{ $item['url'] }}" data-track-click="search_result" data-track-label="{{ $item['title'] }}" class="flex gap-4 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-ink/5 transition hover:shadow-md hover:ring-brand-200">
                        @if ($item['image'])
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['title'] }}" class="h-20 w-20 shrink-0 rounded-xl object-cover">
                        @else
                            <span class="flex h-20 w-20 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700">
                                @if ($item['type'] === 'Layanan')
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.364l-3.5-3.5 3.5-3.5M6.75 12h11.25"/></svg>
                                @elseif ($item['type'] === 'Artikel')
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                                @elseif ($item['type'] === 'Paket')
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.5l7.5 4.5-7.5 4.5-7.5-4.5L12 4.5z"/></svg>
                                @else
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                @endif
                            </span>
                        @endif
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide
                                    @if($item['type']==='Layanan') bg-brand-700/10 text-brand-700
                                    @elseif($item['type']==='Artikel') bg-gold-500/20 text-brand-800
                                    @elseif($item['type']==='Paket') bg-amber-100 text-amber-800
                                    @elseif($item['type']==='Lokasi') bg-emerald-50 text-emerald-700
                                    @else bg-ink/5 text-ink/70 @endif
                                ">{{ $item['type'] }}</span>
                                @if ($item['badge'])
                                    <span class="text-xs text-ink/40">{{ $item['badge'] }}</span>
                                @endif
                            </div>
                            <h3 class="mt-1 font-display text-base font-semibold leading-tight text-brand-800 line-clamp-1">{{ $item['title'] }}</h3>
                            @if ($item['excerpt'])
                                <p class="mt-1 line-clamp-2 text-sm leading-relaxed text-ink/60">{{ $item['excerpt'] }}</p>
                            @endif
                        </div>
                        <span class="hidden shrink-0 items-center text-brand-300 sm:flex">→</span>
                    </a>
                @endforeach
            </div>
        @elseif ($q === '')
            <div class="mt-10">
                <h2 class="font-display text-lg font-semibold text-brand-800">Pencarian populer</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach (['pijat', 'lulur', 'promo', 'perawatan', 'massage', 'facial'] as $kw)
                        <a href="{{ route('search.index', ['q' => $kw]) }}" class="rounded-full border border-brand-200 bg-white px-4 py-2 text-sm font-medium text-brand-700 transition hover:bg-brand-50">{{ $kw }}</a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</x-layouts.app>
