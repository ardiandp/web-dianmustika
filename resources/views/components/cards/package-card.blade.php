@props(['package'])

@php
    $hasPromo = $package->hasPromo();
    $message = 'Halo Dian Mustika, saya tertarik dengan paket '.$package->name;
@endphp

<div class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-ink/5 transition hover:-translate-y-1 hover:shadow-lg {{ $hasPromo ? 'ring-2 ring-gold-400' : '' }}">
    <div class="relative aspect-[3/2] overflow-hidden">
        @if ($package->image)
            <img src="{{ asset('storage/'.$package->image) }}" alt="{{ $package->alt_text ?: $package->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center bg-brand-100 font-display text-2xl text-brand-400">{{ $package->name[0] ?? 'P' }}</div>
        @endif
        @if ($hasPromo)
            <span class="absolute left-3 top-3 rounded-full bg-gold-500 px-3 py-1 text-xs font-bold uppercase tracking-wide text-brand-950 shadow">Promo</span>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-5">
        <h3 class="font-display text-xl font-semibold text-brand-800">{{ $package->name }}</h3>
        <p class="mt-2 flex-1 text-sm leading-relaxed text-ink/70 line-clamp-3">{{ $package->description }}</p>

        <div class="mt-4 border-t border-ink/5 pt-4">
            @if ($hasPromo)
                <div class="flex items-baseline gap-2">
                    <span class="text-sm text-ink/50 line-through">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                    <span class="font-display text-2xl font-semibold text-brand-700">Rp {{ number_format($package->promo_price, 0, ',', '.') }}</span>
                </div>
                @if ($package->starts_at && $package->ends_at)
                    <p class="mt-1 text-xs text-ink/50">Berlaku s.d. {{ $package->ends_at->format('d M Y') }}</p>
                @endif
            @else
                <div class="font-display text-2xl font-semibold text-brand-700">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
            @endif

            <a
                href="{{ App\Services\WhatsAppService::url($message) }}"
                target="_blank"
                rel="noopener"
                class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-800"
            >
                Pesan Sekarang
            </a>
        </div>
    </div>
</div>
