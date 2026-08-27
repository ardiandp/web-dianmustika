<x-layouts.app :seo="$seo">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-brand-950">
        <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle at 20% 20%, #e3c98e 0, transparent 30%), radial-gradient(circle at 80% 70%, #dca8ae 0, transparent 35%);"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <nav class="mb-5 flex flex-wrap items-center gap-2 text-xs uppercase tracking-widest text-brand-100/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-gold-400">Beranda</a>
                <span>/</span>
                <a href="{{ route('packages.index') }}" class="transition hover:text-gold-400">Paket & Promo</a>
                <span>/</span>
                <span class="text-gold-400">{{ $package->name }}</span>
            </nav>

            <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-2">
                <div>
                    @if ($package->hasPromo())
                        <span class="inline-block rounded-full bg-gold-500 px-3 py-1 text-xs font-bold uppercase tracking-wide text-brand-950">Promo Aktif</span>
                    @endif
                    <h1 class="mt-4 font-display text-4xl font-semibold text-cream sm:text-5xl">{{ $package->name }}</h1>
                    <p class="mt-4 max-w-xl leading-relaxed text-brand-100/75">{{ $package->description }}</p>
                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        @php
                            $waMsg = 'Halo Dian Mustika, saya tertarik dengan paket '.$package->name.'.';
                            $waUrl = App\Services\WhatsAppService::url($waMsg);
                        @endphp
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener" data-track-click="whatsapp_paket" data-track-label="{{ $package->name }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#25D366] px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:brightness-95">
                            Pesan via WhatsApp
                        </a>
                        <span class="inline-flex items-center justify-center rounded-full border border-cream/30 px-7 py-3.5 text-sm font-semibold text-cream">
                            @if ($package->hasPromo())
                                <span class="mr-2 text-sm line-through opacity-60">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                Rp {{ number_format($package->promo_price, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    @if ($package->starts_at || $package->ends_at)
                        <p class="mt-3 text-xs text-brand-100/50">
                            Periode: {{ $package->starts_at?->format('d M Y') ?? '—' }} — {{ $package->ends_at?->format('d M Y') ?? '—' }}
                        </p>
                    @endif
                </div>
                <div class="overflow-hidden rounded-[2rem] shadow-2xl">
                    @if ($package->image)
                        <img src="{{ asset('storage/'.$package->image) }}" alt="{{ $package->alt_text ?: $package->name }}" class="h-[380px] w-full object-cover" fetchpriority="high">
                    @else
                        <div class="flex h-[380px] w-full items-center justify-center bg-brand-800 font-display text-4xl text-brand-400">{{ $package->name[0] ?? 'P' }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Detail --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="space-y-10 lg:col-span-2">
                <div>
                    <h2 class="font-display text-2xl font-semibold text-brand-800">Tentang Paket Ini</h2>
                    <p class="mt-4 leading-relaxed text-ink/70">{{ $package->description }}</p>
                </div>

                @if ($package->services->isNotEmpty())
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Layanan Termasuk</h2>
                        <p class="mt-2 text-sm text-ink/60">Paket ini mencakup {{ $package->services->count() }} layanan pilihan:</p>
                        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @foreach ($package->services as $service)
                                <x-cards.service-card :service="$service" />
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex flex-wrap items-center justify-between gap-4 border-y border-brand-100 py-6">
                    <x-public.share-buttons :title="$package->name" />
                    <span class="text-xs text-ink/40">Bagikan paket ini</span>
                </div>
            </div>

            <aside class="space-y-6 lg:sticky lg:top-6 lg:self-start">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                    <h3 class="font-display text-lg font-semibold text-brand-800">Rincian Harga</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-ink/50">Harga Normal</dt>
                            <dd class="font-semibold text-brand-800">Rp {{ number_format($package->price, 0, ',', '.') }}</dd>
                        </div>
                        @if ($package->hasPromo())
                            <div class="flex justify-between gap-4">
                                <dt class="text-ink/50">Harga Promo</dt>
                                <dd class="font-semibold text-gold-600">Rp {{ number_format($package->promo_price, 0, ',', '.') }}</dd>
                            </div>
                            @if ($package->ends_at)
                                <div class="flex justify-between gap-4">
                                    <dt class="text-ink/50">Berlaku s.d.</dt>
                                    <dd class="font-medium text-brand-800">{{ $package->ends_at->format('d M Y') }}</dd>
                                </div>
                            @endif
                        @endif
                    </dl>
                    @php $waMsg2 = 'Halo Dian Mustika, saya ingin pesan paket '.$package->name.'.'; @endphp
                    <a href="{{ App\Services\WhatsAppService::url($waMsg2) }}" target="_blank" rel="noopener" data-track-click="cta_paket_sidebar" data-track-label="{{ $package->name }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-800">
                        Pesan Sekarang
                    </a>
                    <p class="mt-3 text-center text-xs text-ink/50">Konsultasi gratis via WhatsApp</p>
                </div>
            </aside>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-800 via-brand-700 to-brand-900 px-6 py-12 text-center shadow-xl sm:px-12">
            <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(circle at 10% 20%, #e3c98e 0, transparent 30%), radial-gradient(circle at 90% 80%, #e3c98e 0, transparent 25%);"></div>
            <div class="relative">
                <h2 class="font-display text-3xl font-semibold text-cream">Ambil Promo {{ $package->name }} Sekarang</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm text-brand-100/80">Hubungi kami untuk reservasi atau tanya detail paket ini. Tim Dian Mustika siap membantu.</p>
                @php $waBottom = 'Halo Dian Mustika, saya ingin ambil promo paket '.$package->name.'.'; @endphp
                <a href="{{ App\Services\WhatsAppService::url($waBottom) }}" target="_blank" rel="noopener" class="mt-6 inline-flex items-center gap-2 rounded-full bg-[#25D366] px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:brightness-95">
                    Hubungi via WhatsApp
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
