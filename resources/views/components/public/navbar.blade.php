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
    x-data="{ open: false, scrolled: false }"
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
            <a
                href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya ingin konsultasi perawatan.') }}"
                target="_blank"
                rel="noopener"
                class="hidden items-center gap-2 rounded-full bg-[#25D366] px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:brightness-95 sm:inline-flex"
            >
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24s-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.24-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/>
                </svg>
                WhatsApp
            </a>

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
