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
                    <a href="{{ route('services.index', ['kategori' => $service->category->slug]) }}" class="transition hover:text-gold-400">{{ $service->category->name }}</a>
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
                        @php
                            $heroWaMessage = 'Halo Dian Mustika, saya ingin mendapatkan informasi mengenai layanan '.$service->name.'.';
                            $heroCtaUrl = $service->cta_url ?: App\Services\WhatsAppService::url($heroWaMessage);
                            $heroCtaText = $service->cta_text ?: 'Konsultasi via WhatsApp';
                            $isHeroWa = empty($service->cta_url);
                        @endphp
                        <a href="{{ $heroCtaUrl }}" @if($isHeroWa) target="_blank" rel="noopener" @endif class="inline-flex items-center justify-center gap-2 rounded-full {{ $isHeroWa ? 'bg-[#25D366]' : 'bg-brand-700' }} px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:brightness-95">
                            {{ $heroCtaText }}
                        </a>
                        @if ($service->displayPrice())
                            <span class="inline-flex items-center justify-center rounded-full border border-cream/30 px-7 py-3.5 text-sm font-semibold text-cream">
                                {{ $service->displayPrice() }}
                                @if ($service->tipe_harga === 'mulai_dari')
                                    <span class="ml-1 text-xs opacity-70">(Mulai dari)</span>
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="overflow-hidden rounded-[2rem] shadow-2xl">
                    @if ($service->image)
                        <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->alt_text ?: $service->name }}" class="h-[380px] w-full object-cover" fetchpriority="high">
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
                {{-- Deskripsi Lengkap --}}
                @if ($service->description)
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Deskripsi</h2>
                        <div class="prose-content mt-4">{!! $service->description !!}</div>
                    </div>
                @endif

                {{-- Manfaat --}}
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

                {{-- Cocok Untuk --}}
                @if ($service->cocok_untuk)
                    <div class="rounded-2xl bg-brand-50 p-6 ring-1 ring-brand-100">
                        <h2 class="font-display text-xl font-semibold text-brand-800">Cocok Untuk</h2>
                        <p class="mt-3 text-sm leading-relaxed text-ink/70">{{ $service->cocok_untuk }}</p>
                    </div>
                @endif

                {{-- Perhatian --}}
                @if ($service->perhatian)
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6">
                        <h2 class="flex items-center gap-2 font-display text-xl font-semibold text-amber-800">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                            Perhatian
                        </h2>
                        <p class="mt-3 text-sm leading-relaxed text-amber-900/80">{{ $service->perhatian }}</p>
                    </div>
                @endif

                {{-- Galeri --}}
                @if ($service->galleries->isNotEmpty())
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Galeri</h2>
                        <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3">
                            @foreach ($service->galleries as $gallery)
                                <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-ink/5">
                                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->alt_text ?: $service->name }}" class="h-48 w-full object-cover" loading="lazy">
                                    @if ($gallery->caption)
                                        <p class="px-3 py-2 text-xs text-ink/60">{{ $gallery->caption }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Video --}}
                @if ($service->video_url)
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Video</h2>
                        <div class="mt-5 overflow-hidden rounded-2xl shadow-sm ring-1 ring-ink/5">
                            @php
                                $videoUrl = $service->video_url;
                                // Convert YouTube watch URL to embed
                                if (str_contains($videoUrl, 'youtube.com/watch')) {
                                    parse_str(parse_url($videoUrl, PHP_URL_QUERY) ?? '', $ytParams);
                                    if (!empty($ytParams['v'])) {
                                        $videoUrl = 'https://www.youtube.com/embed/' . $ytParams['v'];
                                    }
                                } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                    $ytId = basename(parse_url($videoUrl, PHP_URL_PATH) ?? '');
                                    if ($ytId) $videoUrl = 'https://www.youtube.com/embed/' . $ytId;
                                }
                            @endphp
                            <div class="relative aspect-video">
                                <iframe src="{{ $videoUrl }}" title="Video {{ $service->name }}" class="absolute inset-0 h-full w-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Lokasi Tersedia --}}
                @if ($service->locations->isNotEmpty())
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Layanan Tersedia di</h2>
                        <p class="mt-2 text-sm text-ink/60">Kunjungi cabang terdekat untuk mendapatkan layanan ini.</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($service->locations as $location)
                                <a href="{{ route('locations.show', $location) }}" class="rounded-full bg-white px-4 py-2 text-sm font-medium text-brand-700 ring-1 ring-brand-200 transition hover:bg-brand-50">
                                    {{ $location->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- FAQ --}}
                @if ($service->faqs->isNotEmpty())
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Pertanyaan Umum</h2>
                        <div class="mt-5 space-y-3">
                            @foreach ($service->faqs as $faq)
                                <x-cards.faq-item :faq="$faq" />
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Artikel Terkait --}}
                @if (isset($relatedArticles) && $relatedArticles->isNotEmpty())
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Artikel Terkait</h2>
                        <p class="mt-2 text-sm text-ink/60">Baca artikel yang berkaitan dengan layanan ini.</p>
                        <div class="mt-5 grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @foreach ($relatedArticles as $article)
                                <x-cards.article-card :article="$article" />
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6 lg:sticky lg:top-6 lg:self-start">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                    <h3 class="font-display text-lg font-semibold text-brand-800">Informasi Treatment</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        @if ($service->duration)
                            <div class="flex justify-between gap-4">
                                <dt class="text-ink/50">Durasi</dt>
                                <dd class="font-semibold text-brand-800">{{ $service->duration }}</dd>
                            </div>
                        @endif
                        @if ($service->displayPrice())
                            <div class="flex justify-between gap-4">
                                <dt class="text-ink/50">Harga</dt>
                                <dd class="font-semibold text-brand-800 text-right">
                                    {{ $service->displayPrice() }}
                                    @if ($service->tipe_harga === 'mulai_dari')
                                        <span class="block text-xs font-normal text-ink/50">Mulai dari</span>
                                    @elseif ($service->tipe_harga === 'per_lokasi')
                                        <span class="block text-xs font-normal text-ink/50">Per lokasi</span>
                                    @endif
                                </dd>
                            </div>
                        @elseif ($service->tipe_harga === 'hubungi_kami')
                            <div class="flex justify-between gap-4">
                                <dt class="text-ink/50">Harga</dt>
                                <dd class="font-semibold text-brand-800">Hubungi Kami</dd>
                            </div>
                        @endif
                        @if ($service->category)
                            <div class="flex justify-between gap-4">
                                <dt class="text-ink/50">Kategori</dt>
                                <dd class="font-semibold text-brand-800">{{ $service->category->name }}</dd>
                            </div>
                        @endif
                    </dl>
                    @if ($service->note)
                        <div class="mt-4 rounded-xl bg-brand-50 p-4 text-xs leading-relaxed text-brand-800">
                            {{ $service->note }}
                        </div>
                    @endif
                    @php
                        $waMessage = 'Halo Dian Mustika, saya ingin reservasi layanan '.$service->name.'.';
                        $ctaUrl = $service->cta_url ?: App\Services\WhatsAppService::url($waMessage);
                        $ctaText = $service->cta_text ?: 'Reservasi Sekarang';
                        $isWa = empty($service->cta_url);
                    @endphp
                    <a href="{{ $ctaUrl }}" @if($isWa) target="_blank" rel="noopener" @endif class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-800">
                        {{ $ctaText }}
                    </a>
                    @if ($service->locations->isNotEmpty())
                        <p class="mt-3 text-center text-xs text-ink/50">Tersedia di {{ $service->locations->pluck('name')->join(', ') }}</p>
                    @endif
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

                {{-- Mini CTA with location-specific WA --}}
                @if ($service->locations->isNotEmpty())
                    <div class="rounded-2xl bg-brand-950 p-6 text-cream">
                        <h3 class="font-display text-lg font-semibold">Reservasi di Lokasi</h3>
                        <p class="mt-2 text-sm text-brand-100/70">Pilih lokasi untuk reservasi via WhatsApp.</p>
                        <div class="mt-4 space-y-2">
                            @foreach ($service->locations as $location)
                                @php $locWa = App\Services\WhatsAppService::url('Halo Dian Mustika, saya ingin reservasi layanan '.$service->name.' di '.$location->name.'.'); @endphp
                                <a href="{{ $locWa }}" target="_blank" rel="noopener" class="flex items-center justify-between rounded-xl bg-white/10 px-4 py-3 text-sm font-medium transition hover:bg-white/20">
                                    <span>{{ $location->name }}</span>
                                    <span class="text-xs opacity-60">WhatsApp →</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    {{-- Layanan Terkait --}}
    @if ($related->isNotEmpty())
        <section class="border-t border-brand-100 bg-white py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sections.section-heading title="Layanan Terkait" description="Mungkin Anda juga tertarik dengan perawatan berikut.">
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

    {{-- Bottom CTA --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-800 via-brand-700 to-brand-900 px-6 py-14 text-center shadow-xl sm:px-12">
            <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(circle at 10% 20%, #e3c98e 0, transparent 30%), radial-gradient(circle at 90% 80%, #e3c98e 0, transparent 25%);"></div>
            <div class="relative">
                <h2 class="font-display text-3xl font-semibold text-cream sm:text-4xl">Siap Mencoba {{ $service->name }}?</h2>
                <p class="mx-auto mt-4 max-w-2xl text-brand-100/80">Konsultasikan kebutuhan perawatan Anda dengan tim kami melalui WhatsApp. Kami siap membantu Anda menemukan perawatan yang paling sesuai.</p>
                <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                    @php
                        $bottomWa = 'Halo Dian Mustika, saya ingin reservasi layanan '.$service->name.'.';
                        $bottomUrl = $service->cta_url ?: App\Services\WhatsAppService::url($bottomWa);
                        $bottomText = $service->cta_text ?: 'Reservasi Sekarang';
                        $bottomIsWa = empty($service->cta_url);
                    @endphp
                    <a href="{{ $bottomUrl }}" @if($bottomIsWa) target="_blank" rel="noopener" @endif class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#25D366] px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:brightness-95 sm:w-auto">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24s-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.24-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/></svg>
                        {{ $bottomText }}
                    </a>
                    <a href="{{ route('services.index') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-cream/40 px-7 py-3.5 text-sm font-semibold text-cream transition hover:bg-cream/10 sm:w-auto">
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
