@props(['title', 'description' => '', 'crumb' => 'Beranda'])

<section class="relative overflow-hidden bg-brand-950">
    <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle at 20% 20%, #e3c98e 0, transparent 30%), radial-gradient(circle at 80% 70%, #dca8ae 0, transparent 35%);"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-20 text-center sm:px-6 lg:px-8">
        <nav class="mb-4 flex items-center justify-center gap-2 text-xs uppercase tracking-widest text-brand-100/60" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="transition hover:text-gold-400">Beranda</a>
            @if (trim($crumb) !== '' && trim($crumb) !== 'Beranda')
                <span class="text-brand-100/40">/</span>
                <span class="text-gold-400">{{ $crumb }}</span>
            @endif
        </nav>
        <h1 class="font-display text-4xl font-semibold text-cream sm:text-5xl">{{ $title }}</h1>
        @if ($description)
            <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-brand-100/70">{{ $description }}</p>
        @endif
    </div>
</section>
