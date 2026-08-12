@props(['location'])

@php
    $hours = $location->opening_hours;
    $firstHours = is_array($hours) && count($hours) ? reset($hours) : null;
@endphp

<div class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-ink/5 transition hover:-translate-y-1 hover:shadow-lg">
    <div class="relative aspect-[3/2] overflow-hidden">
        @if ($location->image)
            <img src="{{ asset('storage/'.$location->image) }}" alt="{{ $location->alt_text ?: $location->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center bg-brand-100 font-display text-2xl text-brand-400">{{ $location->name[0] ?? 'L' }}</div>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-5">
        <h3 class="font-display text-xl font-semibold text-brand-800">{{ $location->name }}</h3>
        <p class="mt-2 flex-1 text-sm leading-relaxed text-ink/70">{{ $location->address }}</p>
        <div class="mt-4 space-y-1.5 text-sm text-ink/70">
            @if ($firstHours)
                <p class="flex items-center gap-2"><span class="text-gold-600">🕒</span> {{ $firstHours }}</p>
            @endif
            @if ($location->whatsapp)
                <p class="flex items-center gap-2"><span class="text-gold-600">☎</span> {{ App\Services\WhatsAppService::display() }}</p>
            @endif
        </div>
        <div class="mt-4 flex gap-2 border-t border-ink/5 pt-4">
            <a href="{{ route('locations.show', $location) }}" class="flex-1 rounded-full border border-brand-200 px-4 py-2 text-center text-sm font-semibold text-brand-700 transition hover:bg-brand-50">Detail</a>
            <a
                href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika '.$location->name.', saya ingin bertanya.') }}"
                target="_blank"
                rel="noopener"
                class="flex-1 rounded-full bg-brand-700 px-4 py-2 text-center text-sm font-semibold text-white transition hover:bg-brand-800"
            >
                WhatsApp
            </a>
        </div>
    </div>
</div>
