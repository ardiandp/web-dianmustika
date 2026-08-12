@props(['service'])

<a href="{{ route('services.show', $service) }}" class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-ink/5 transition hover:-translate-y-1 hover:shadow-lg">
    <div class="relative aspect-[3/2] overflow-hidden">
        @if ($service->image)
            <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->alt_text ?: $service->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center bg-brand-100 font-display text-2xl text-brand-400">{{ $service->name[0] ?? 'D' }}</div>
        @endif
        @if ($service->category)
            <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-brand-800 backdrop-blur">{{ $service->category->name }}</span>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-5">
        <h3 class="font-display text-xl font-semibold text-brand-800 transition group-hover:text-brand-600">{{ $service->name }}</h3>
        <p class="mt-2 flex-1 text-sm leading-relaxed text-ink/70 line-clamp-3">{{ $service->short_description }}</p>
        <div class="mt-4 flex items-center justify-between border-t border-ink/5 pt-4">
            <div class="text-sm">
                @if ($service->duration)
                    <span class="text-ink/60">{{ $service->duration }}</span>
                @endif
            </div>
            <span class="inline-flex items-center gap-1 text-sm font-semibold text-gold-600">
                @if ($service->price)
                    Rp {{ number_format($service->price, 0, ',', '.') }}
                @else
                    Konsultasi
                @endif
            </span>
        </div>
    </div>
</a>
