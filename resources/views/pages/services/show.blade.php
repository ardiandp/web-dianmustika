<x-layouts.app :seo="$seo">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-brand-950">
        <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle at 20% 20%, #e3c98e 0, transparent 30%), radial-gradient(circle at 80% 70%, #dca8ae 0, transparent 35%);"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <nav class="mb-5 flex flex-wrap items-center gap-2 text-xs uppercase tracking-widest text-brand-100/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-gold-400">Beranda</a>
                <span>/</span>
                <a href="{{ route('services.index') }}" class="transition hover:text-gold-400">Layanan</a>
                @if ($service->category)
                    <span>/</span>
                    <span class="text-brand-100/40">{{ $service->category->name }}</span>
                @endif
                <span>/</span>
                <span class="text-gold-400">{{ $service->name }}</span>
            </nav>

            <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-2">
                <div>
                    @if ($service->category)
                        <span class="inline-block rounded-full bg-gold-500/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-gold-400">{{ $service->category->name }}</span>
                    @endif
                    <h1 class="mt-4 font-display text-4xl font-semibold text-cream sm:text-5xl">{{ $service->name }}</h1>
                    <p class="mt-4 max-w-xl leading-relaxed text-brand-100/75">{{ $service->short_description }}</p>
                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya ingin konsultasi untuk perawatan '.$service->name.'.') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#25D366] px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:brightness-95">
                            Konsultasi via WhatsApp
                        </a>
                        @if ($service->price)
                            <a href="{{ route('contact.index') }}" class="inline-flex items-center justify-center rounded-full border border-cream/30 px-7 py-3.5 text-sm font-semibold text-cream transition hover:bg-cream/10">
                                Rp {{ number_format($service->price, 0, ',', '.') }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="overflow-hidden rounded-[2rem] shadow-2xl">
                    @if ($service->image)
                        <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->alt_text ?: $service->name }}" class="h-[380px] w-full object-cover">
                    @else
                        <div class="flex h-[380px] w-full items-center justify-center bg-brand-800 font-display text-4xl text-brand-400">{{ $service->name[0] ?? 'D' }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Body --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="space-y-12 lg:col-span-2">
                @if ($service->description)
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Deskripsi</h2>
                        <div class="prose-content mt-4">{{ $service->description }}</div>
                    </div>
                @endif

                @if ($service->benefits)
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Manfaat Perawatan</h2>
                        <ul class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach ($service->benefits as $benefit)
                                <li class="flex items-start gap-3 rounded-xl bg-white p-4 ring-1 ring-ink/5">
                                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gold-500 text-brand-950">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span class="text-sm text-ink/80">{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($service->locations->isNotEmpty())
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Tersedia di</h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($service->locations as $location)
                                <a href="{{ route('locations.show', $location) }}" class="rounded-full bg-white px-4 py-2 text-sm font-medium text-brand-700 ring-1 ring-brand-200 transition hover:bg-brand-50">
                                    {{ $location->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                    <h3 class="font-display text-lg font-semibold text-brand-800">Informasi Treatment</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        @if ($service->duration)
                            <div class="flex justify-between gap-4">
                                <dt class="text-ink/50">Durasi</dt>
                                <dd class="font-semibold text-brand-800">{{ $service->duration }}</dd>
                            </div>
                        @endif
                        @if ($service->price)
                            <div class="flex justify-between gap-4">
                                <dt class="text-ink/50">Harga</dt>
                                <dd class="font-semibold text-brand-800">Rp {{ number_format($service->price, 0, ',', '.') }}</dd>
                            </div>
                        @endif
                    </dl>
                    @if ($service->note)
                        <div class="mt-4 rounded-xl bg-brand-50 p-4 text-xs leading-relaxed text-brand-800">
                            {{ $service->note }}
                        </div>
                    @endif
                    <a href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya ingin reservasi perawatan '.$service->name.'.') }}" target="_blank" rel="noopener" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-800">
                        Reservasi Sekarang
                    </a>
                </div>

                @if ($service->faqs->isNotEmpty())
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                        <h3 class="font-display text-lg font-semibold text-brand-800">FAQ Layanan</h3>
                        <div class="mt-4 space-y-3">
                            @foreach ($service->faqs as $faq)
                                <x-cards.faq-item :faq="$faq" />
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <section class="border-t border-brand-100 bg-white py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sections.section-heading title="Layanan Lainnya" description="Mungkin Anda juga tertarik dengan perawatan berikut.">
                    Lihat Juga
                </x-sections.section-heading>
                <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <x-cards.service-card :service="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-sections.cta title="Siap Mencoba {{ $service->name }}?" />
</x-layouts.app>
