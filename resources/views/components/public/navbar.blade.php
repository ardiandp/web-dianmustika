@props([])

@php
    $nav = [
        ['label' => 'Beranda', 'patterns' => ['home']],
        ['label' => 'Tentang', 'patterns' => ['about']],
        ['label' => 'Layanan', 'patterns' => ['services.index', 'services.show']],
        ['label' => 'Paket', 'patterns' => ['packages.index']],
        ['label' => 'Artikel', 'patterns' => ['articles.index', 'articles.show', 'articles.category']],
        ['label' => 'Lokasi', 'patterns' => ['locations.index', 'locations.show']],
    ];

    $mobile = [
        ['label' => 'Beranda', 'patterns' => ['home']],
        ['label' => 'Tentang Kami', 'patterns' => ['about']],
        ['label' => 'Layanan', 'patterns' => ['services.index', 'services.show']],
        ['label' => 'Paket & Promo', 'patterns' => ['packages.index']],
        ['label' => 'Galeri', 'patterns' => ['galleries.index']],
        ['label' => 'Testimoni', 'patterns' => ['testimonials.index']],
        ['label' => 'Artikel', 'patterns' => ['articles.index', 'articles.show', 'articles.category']],
        ['label' => 'Lokasi', 'patterns' => ['locations.index', 'locations.show']],
        ['label' => 'FAQ', 'patterns' => ['faqs.index']],
        ['label' => 'Kontak', 'patterns' => ['contact.index']],
    ];

    $isActive = fn (array $patterns) => collect($patterns)->contains(fn ($p) => request()->routeIs($p));
@endphp

<header
    x-data="{ open: false, scrolled: false, searchMobileOpen: false }"
    @scroll.window.passive="scrolled = window.scrollY > 40"
    class="sticky top-0 z-50 w-full transition-all duration-300"
    :class="scrolled ? 'border-b border-ink/5 bg-cream/95 shadow-sm backdrop-blur' : 'border-b border-transparent bg-cream/90 backdrop-blur'"
>
    <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Dian Mustika">
            @php $logo = App\Models\Setting::get('logo'); @endphp
            @if ($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ config('app.name') }}" class="h-10 w-auto rounded-full object-contain">
            @else
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-brand-600 to-brand-800 font-display text-xl font-semibold text-cream shadow-md">
                    D
                </span>
            @endif
            <span class="leading-tight">
                <span class="block font-display text-xl font-semibold tracking-wide text-brand-800">{{ App\Models\Setting::get('site_name', 'Dian Mustika') }}</span>
                <span class="block text-[11px] uppercase tracking-[0.2em] text-gold-600">{{ App\Models\Setting::get('site_tagline', 'Beauty & Wellness') }}</span>
            </span>
        </a>

        <div class="hidden items-center gap-1 lg:flex">
            @foreach ($nav as $item)
                <a
                    href="{{ route($item['patterns'][0]) }}"
                    class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ $isActive($item['patterns']) ? 'bg-brand-100 text-brand-800' : 'text-ink/70 hover:bg-brand-50 hover:text-brand-800' }}"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <div class="flex items-center gap-3">
            {{-- Search (desktop elegant, kanan atas) --}}
            <div x-data="{ q: '', suggestions: [], open: false, fetchSuggest() { if(this.q.trim().length < 2){ this.suggestions=[]; this.open=false; return; } fetch('{{ route('search.suggest') }}?q='+encodeURIComponent(this.q.trim())).then(r=>r.json()).then(d=>{ this.suggestions=d; this.open=d.length>0; }).catch(()=>{}) } }" class="relative hidden lg:block">
                <form action="{{ route('search.index') }}" method="GET" class="relative" @submit="if(!q.trim()) $event.preventDefault()">
                    <input
                        x-model="q"
                        @input.debounce.300ms="fetchSuggest()"
                        @focus="if(suggestions.length) open=true"
                        @keydown.escape.window="open=false"
                        @click.away="open=false"
                        type="search"
                        name="q"
                        placeholder="Cari layanan, paket..."
                        autocomplete="off"
                        class="w-52 rounded-full border border-brand-200 bg-white py-2 pl-4 pr-4 text-sm text-ink placeholder:text-ink/40 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 xl:w-60"
                    >
                </form>
                <div x-show="open && suggestions.length" x-cloak @click.away="open=false" class="absolute right-0 z-50 mt-2 max-h-96 w-80 overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-ink/10">
                    <div class="max-h-80 overflow-y-auto py-2">
                        <template x-for="item in suggestions" :key="item.url">
                            <a :href="item.url" class="flex items-center gap-3 px-4 py-2.5 text-sm transition hover:bg-brand-50">
                                <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide"
                                    :class="item.type==='Layanan' ? 'bg-brand-700/10 text-brand-700' : item.type==='Artikel' ? 'bg-gold-500/20 text-brand-800' : item.type==='Paket' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-50 text-emerald-700'"
                                    x-text="item.type"></span>
                                <span class="line-clamp-1 flex-1 text-ink/80" x-text="item.title"></span>
                            </a>
                        </template>
                    </div>
                    <a :href="'{{ route('search.index') }}?q=' + encodeURIComponent(q)" class="block border-t border-ink/5 bg-cream px-4 py-2.5 text-center text-xs font-semibold text-brand-700 hover:bg-brand-50">Lihat semua hasil →</a>
                </div>
            </div>

            <a
                href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya ingin konsultasi perawatan.') }}"
                target="_blank"
                rel="noopener"
                data-track-click="whatsapp_navbar"
                data-track-label="Navbar WhatsApp"
                class="hidden items-center gap-2 rounded-full bg-[#25D366] px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:brightness-95 sm:inline-flex"
            >
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24s-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.24-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/>
                </svg>
                WhatsApp
            </a>

            <button
                @click="searchMobileOpen = !searchMobileOpen"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-ink/60 ring-1 ring-brand-200 transition hover:bg-brand-50 hover:text-brand-800 lg:hidden"
                aria-label="Cari"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z"/></svg>
            </button>

            <button
                @click="open = !open"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-ink/70 hover:bg-brand-50 hover:text-brand-800 lg:hidden"
                aria-label="Menu"
                aria-expanded="open"
            >
                <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </nav>

    {{-- Mobile search overlay --}}
    <div x-cloak x-show="searchMobileOpen" x-transition class="border-t border-ink/5 bg-cream px-4 py-3 lg:hidden">
        <form action="{{ route('search.index') }}" method="GET" class="relative">
            <input type="search" name="q" placeholder="Cari layanan, paket, artikel..." autocomplete="off" class="w-full rounded-full border border-brand-200 bg-white py-2.5 pl-4 pr-20 text-sm placeholder:text-ink/40 focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            <button type="submit" class="absolute inset-y-1 right-1 rounded-full bg-brand-700 px-5 text-xs font-semibold text-white">Cari</button>
        </form>
    </div>

    <div x-cloak x-show="open" x-transition x-collapse class="border-t border-ink/5 bg-cream lg:hidden">
        <div class="space-y-1 px-4 py-4">
            @foreach ($mobile as $item)
                <a
                    href="{{ route($item['patterns'][0]) }}"
                    @click="open = false"
                    class="block rounded-lg px-4 py-2.5 text-sm font-medium {{ $isActive($item['patterns']) ? 'bg-brand-100 text-brand-800' : 'text-ink/80 hover:bg-brand-50 hover:text-brand-800' }}"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach

            <a
                href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya ingin konsultasi perawatan.') }}"
                target="_blank"
                rel="noopener"
                data-track-click="whatsapp_navbar_mobile"
                data-track-label="Navbar Mobile WhatsApp"
                class="mt-3 flex items-center justify-center gap-2 rounded-full bg-[#25D366] px-5 py-3 text-sm font-semibold text-white shadow-md"
            >
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24s-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.24-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/>
                </svg>
                Hubungi via WhatsApp
            </a>
        </div>
    </div>
</header>
