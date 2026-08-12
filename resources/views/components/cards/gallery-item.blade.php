@props(['gallery'])

<figure class="group relative overflow-hidden rounded-2xl">
    <div class="aspect-square overflow-hidden">
        <img src="{{ asset('storage/'.$gallery->image) }}" alt="{{ $gallery->alt_text }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
    </div>
    <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-brand-950/80 via-brand-950/20 to-transparent p-4 opacity-0 transition group-hover:opacity-100">
        <div>
            <span class="inline-block rounded-full bg-gold-500/90 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-brand-950">
                {{ match ($gallery->category) {
                    'tempat' => 'Tempat',
                    'treatment' => 'Treatment',
                    'aktivitas' => 'Aktivitas',
                    'promo' => 'Promo',
                    default => ucfirst($gallery->category),
                } }}
            </span>
            @if ($gallery->caption)
                <p class="mt-2 text-sm text-cream">{{ $gallery->caption }}</p>
            @endif
        </div>
    </div>
</figure>
