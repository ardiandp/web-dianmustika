<x-layouts.app :seo="$seo">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-brand-950">
        <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle at 20% 20%, #e3c98e 0, transparent 30%), radial-gradient(circle at 80% 70%, #dca8ae 0, transparent 35%);"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <nav class="mb-5 flex flex-wrap items-center gap-2 text-xs uppercase tracking-widest text-brand-100/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-gold-400">Beranda</a>
                <span>/</span>
                <a href="{{ route('locations.index') }}" class="transition hover:text-gold-400">Lokasi</a>
                <span>/</span>
                <span class="text-gold-400">{{ $location->name }}</span>
            </nav>

            <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-2">
                <div>
                    <h1 class="font-display text-4xl font-semibold text-cream sm:text-5xl">{{ $location->name }}</h1>
                    <p class="mt-4 flex items-start gap-2 text-brand-100/75">
                        <span class="mt-0.5 text-gold-500">●</span>
                        {{ $location->address }}
                    </p>
                    @if ($location->description)
                        <p class="mt-4 max-w-xl leading-relaxed text-brand-100/70">{{ $location->description }}</p>
                    @endif
                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika '.$location->name.', saya ingin reservasi.') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#25D366] px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:brightness-95">
                            WhatsApp {{ $location->name }}
                        </a>
                        @if ($location->google_maps_url)
                            <a href="{{ $location->google_maps_url }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full border border-cream/30 px-7 py-3.5 text-sm font-semibold text-cream transition hover:bg-cream/10">
                                Buka Google Maps
                            </a>
                        @endif
                    </div>
                </div>
                <div class="overflow-hidden rounded-[2rem] shadow-2xl">
                    @if ($location->image)
                        <img src="{{ asset('storage/'.$location->image) }}" alt="{{ $location->alt_text ?: $location->name }}" class="h-[380px] w-full object-cover">
                    @else
                        <div class="flex h-[380px] w-full items-center justify-center bg-brand-800 font-display text-4xl text-brand-400">{{ $location->name[0] ?? 'L' }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="space-y-10 lg:col-span-2">
                @if ($location->services->isNotEmpty())
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">Layanan Tersedia</h2>
                        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @foreach ($location->services as $service)
                                <x-cards.service-card :service="$service" />
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($location->faqs->isNotEmpty())
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">FAQ Lokasi</h2>
                        <div class="mt-5 space-y-3">
                            @foreach ($location->faqs as $faq)
                                <x-cards.faq-item :faq="$faq" />
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                    <h3 class="font-display text-lg font-semibold text-brand-800">Informasi Kontak</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        @if ($location->whatsapp)
                            <div class="flex justify-between gap-4">
                                <dt class="text-ink/50">WhatsApp</dt>
                                <dd><a href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika '.$location->name.'!') }}" target="_blank" rel="noopener" class="font-semibold text-brand-700 hover:text-brand-600">{{ App\Services\WhatsAppService::display() }}</a></dd>
                            </div>
                        @endif
                        @if ($location->phone)
                            <div class="flex justify-between gap-4">
                                <dt class="text-ink/50">Telepon</dt>
                                <dd class="font-semibold text-brand-800">{{ $location->phone }}</dd>
                            </div>
                        @endif
                        @if ($location->email)
                            <div class="flex justify-between gap-4">
                                <dt class="text-ink/50">Email</dt>
                                <dd><a href="mailto:{{ $location->email }}" class="font-semibold text-brand-700 hover:text-brand-600">{{ $location->email }}</a></dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                    <h3 class="font-display text-lg font-semibold text-brand-800">Jam Operasional</h3>
                    <dl class="mt-4 space-y-2 text-sm">
                        @if (is_array($location->opening_hours))
                            @foreach ($location->opening_hours as $day => $hours)
                                <div class="flex justify-between gap-4">
                                    <dt class="text-ink/50">{{ $day }}</dt>
                                    <dd class="font-medium text-brand-800">{{ $hours }}</dd>
                                </div>
                            @endforeach
                        @else
                            <p class="text-ink/50">Informasi jam operasional belum tersedia.</p>
                        @endif
                    </dl>
                </div>
            </aside>
        </div>
    </section>

    <x-sections.cta :title="'Kunjungi '.$location->name" :description="'Hubungi '.$location->name.' melalui WhatsApp untuk reservasi atau informasi lebih lanjut.'" />
</x-layouts.app>
