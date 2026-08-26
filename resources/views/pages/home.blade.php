<x-layouts.app :seo="$seo">

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-brand-100/60 to-cream">
        <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-brand-700/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">
                    {{ App\Models\Setting::get('hero_badge', 'Beauty & Wellness') }}
                </span>
                <h1 class="mt-6 font-display text-4xl font-semibold leading-tight text-brand-800 sm:text-5xl lg:text-6xl">
                    {{ App\Models\Setting::get('hero_heading', 'Perawatan Tubuh & Kecantikan untuk Anda yang Ingin Merawat Diri') }}
                </h1>
                <p class="mt-5 max-w-xl text-base leading-relaxed text-ink/70 sm:text-lg">
                    {{ App\Models\Setting::get('hero_description', 'Dian Mustika membantu Anda merawat diri dengan layanan profesional, nyaman, dan elegan — dari massage relaksasi, slimming, hingga perawatan pasca melahirkan.') }}
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya ingin konsultasi perawatan.') }}" target="_blank" rel="noopener" data-track-click="whatsapp_home_hero" data-track-label="Home Hero" class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-700 px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-brand-800">
                        Konsultasi via WhatsApp
                    </a>
                    <a href="{{ route('services.index') }}" data-track-click="cta_home_lihat_layanan" data-track-label="Home Hero Lihat Layanan" class="inline-flex items-center justify-center gap-2 rounded-full border border-brand-200 bg-white px-7 py-3.5 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">
                        Lihat Layanan
                    </a>
                </div>
                <dl class="mt-10 grid grid-cols-3 gap-6 border-t border-brand-200/50 pt-6">
                    <div>
                        <dt class="font-display text-3xl font-semibold text-brand-800">{{ App\Models\Setting::get('hero_stat1_value', '15+') }}</dt>
                        <dd class="mt-1 text-xs text-ink/60">{{ App\Models\Setting::get('hero_stat1_label', 'Jenis Perawatan') }}</dd>
                    </div>
                    <div>
                        <dt class="font-display text-3xl font-semibold text-brand-800">{{ App\Models\Setting::get('hero_stat2_value', '3') }}</dt>
                        <dd class="mt-1 text-xs text-ink/60">{{ App\Models\Setting::get('hero_stat2_label', 'Lokasi Cabang') }}</dd>
                    </div>
                    <div>
                        <dt class="font-display text-3xl font-semibold text-brand-800">{{ App\Models\Setting::get('hero_stat3_value', '100%') }}</dt>
                        <dd class="mt-1 text-xs text-ink/60">{{ App\Models\Setting::get('hero_stat3_label', 'Terapis Berpengalaman') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="relative">
                <div class="absolute -left-6 -top-6 h-40 w-40 rounded-full bg-gold-300/40 blur-2xl"></div>
                <div class="relative overflow-hidden rounded-[2rem] bg-cream shadow-2xl">
                    @php
                        $heroImage = App\Models\Setting::get('hero_image')
                            ?? App\Models\Gallery::active()->ordered()->value('image');
                    @endphp
                    @if ($heroImage)
                        <img src="{{ asset('storage/'.$heroImage) }}" alt="Suasana perawatan Dian Mustika" fetchpriority="high" class="h-[420px] w-full bg-cream object-contain sm:h-[520px]">
                    @else
                        <div class="flex h-[420px] w-full items-center justify-center bg-brand-200 font-display text-3xl text-brand-700 sm:h-[520px]">Dian Mustika</div>
                    @endif
                </div>
                <div class="absolute -bottom-5 left-6 rounded-2xl bg-white p-4 shadow-xl ring-1 ring-ink/5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#25D366]/10 text-[#25D366]">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24s-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.24-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-brand-800">Konsultasi Gratis</p>
                            <p class="text-xs text-ink/50">Via WhatsApp, setiap hari</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TRUST / KEUNGGULAN --}}
    <section class="border-y border-brand-100 bg-white">
        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-4 py-10 sm:px-6 lg:grid-cols-4 lg:px-8">
            <div class="flex items-center gap-3">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </span>
                <p class="text-sm font-semibold text-brand-800">Terapis Profesional<br><span class="font-normal text-ink/60">Berpengalaman</span></p>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zm0 0a8.949 8.949 0 004.951-1.488A3.987 3.987 0 0013 16h-2a3.987 3.987 0 00-3.951 3.512A8.949 8.949 0 0012 21zm3-11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
                <p class="text-sm font-semibold text-brand-800">Khusus Wanita<br><span class="font-normal text-ink/60">Aman & nyaman</span></p>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                </span>
                <p class="text-sm font-semibold text-brand-800">Bahan Alami<br><span class="font-normal text-ink/60">Herbal & tradisional</span></p>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </span>
                <p class="text-sm font-semibold text-brand-800">Homecare<br><span class="font-normal text-ink/60">Perawatan di rumah</span></p>
            </div>
        </div>
    </section>

    {{-- LAYANAN UNGGULAN --}}
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <x-sections.section-heading title="Layanan Unggulan" description="Beragam perawatan pilihan yang dirancang untuk membantu Anda merawat tubuh dan kecantikan secara menyeluruh.">
            Layanan Kami
        </x-sections.section-heading>

        <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($services as $service)
                <x-cards.service-card :service="$service" />
            @empty
                <p class="col-span-full text-center text-ink/60">Belum ada layanan.</p>
            @endforelse
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white px-7 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">
                Lihat Semua Layanan <span aria-hidden="true">→</span>
            </a>
        </div>
    </section>

    {{-- PAKET / PROMO --}}
    <section class="bg-brand-950 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-sections.section-heading title="Paket & Promo" description="Dapatkan harga spesial dengan paket perawatan pilihan kami. Penawaran berlaku selama periode promo." light>
                Penawaran Spesial
            </x-sections.section-heading>

            <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($packages as $package)
                    <x-cards.package-card :package="$package" />
                @empty
                    <p class="col-span-full text-center text-brand-100/60">Belum ada paket promo.</p>
                @endforelse
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 rounded-full border border-cream/30 px-7 py-3 text-sm font-semibold text-cream transition hover:bg-cream/10">
                    Lihat Semua Paket <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    {{-- TENTANG DIAN MUSTIKA --}}
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <div class="relative order-2 lg:order-1">
                <div class="absolute -right-6 -top-6 h-40 w-40 rounded-full bg-brand-100 blur-2xl"></div>
                @php $aboutImage = App\Models\Gallery::active()->where('category', 'tempat')->value('image'); @endphp
                <div class="relative overflow-hidden rounded-[2rem] shadow-xl">
                    @if ($aboutImage)
                        <img src="{{ asset('storage/'.$aboutImage) }}" alt="Tempat perawatan Dian Mustika" class="h-[420px] w-full object-cover" loading="lazy">
                    @else
                        <div class="flex h-[420px] w-full items-center justify-center bg-brand-100 font-display text-3xl text-brand-700">Dian Mustika</div>
                    @endif
                </div>
            </div>
            <div class="order-1 lg:order-2">
                <x-sections.section-heading title="{{ App\Models\Setting::get('about_heading', 'Tentang Dian Mustika') }}" description="{{ App\Models\Setting::get('about_text', '') }}" align="left">
                    Tentang Kami
                </x-sections.section-heading>
                <ul class="mt-8 space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gold-500 text-brand-950">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <p class="text-sm text-ink/70">Menggabungkan teknik perawatan modern dengan kearifan tradisional.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gold-500 text-brand-950">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <p class="text-sm text-ink/70">Terapis perempuan yang profesional dan ramah.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gold-500 text-brand-950">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <p class="text-sm text-ink/70">Ruang perawatan bersih, nyaman, dan privat.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gold-500 text-brand-950">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <p class="text-sm text-ink/70">Tersedia layanan homecare untuk kenyamanan Anda di rumah.</p>
                    </li>
                </ul>
                <a href="{{ route('about') }}" class="mt-8 inline-flex items-center gap-2 rounded-full bg-brand-700 px-7 py-3 text-sm font-semibold text-white transition hover:bg-brand-800">
                    Kenali Kami Lebih Dekat <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    {{-- MENGAPA MEMILIH KAMI --}}
    <section class="border-y border-brand-100 bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-sections.section-heading title="Mengapa Memilih Dian Mustika" description="Kami hadir untuk memberikan pengalaman perawatan yang menenangkan dan profesional.">
                Keunggulan Kami
            </x-sections.section-heading>

            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="text-center">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-700">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    <h3 class="mt-5 font-display text-lg font-semibold text-brand-800">Terapis Terlatih</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink/60">Tim terapis kami terlatih dan berpengalaman untuk setiap jenis perawatan.</p>
                </div>
                <div class="text-center">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-700">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
                    </span>
                    <h3 class="mt-5 font-display text-lg font-semibold text-brand-800">Layanan Lengkap</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink/60">Beragam perawatan dalam satu tempat, dari relaksasi hingga perawatan khusus.</p>
                </div>
                <div class="text-center">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-700">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </span>
                    <h3 class="mt-5 font-display text-lg font-semibold text-brand-800">Bahan Alami Pilihan</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink/60">Menggunakan bahan-bahan alami yang aman dan nyaman untuk kulit Anda.</p>
                </div>
                <div class="text-center">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-700">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.35c-.924.99-1.101 2.372-.417 3.516.378.633.325 1.413-.149 1.985-.5.6-1.256.882-2.011.7a4.99 4.99 0 01-3.384-2.87 5.012 5.012 0 01-1.3-.68c-.5-.35-1.152-.378-1.68-.07l-.412.24c-1.426.831-3.193.634-4.38-.515a4.5 4.5 0 01-1.126-4.028c.32-1.383.087-2.84-.637-4.068a10.98 10.98 0 01-1.57-4.84A2.25 2.25 0 015.25 6.75h.008a2.25 2.25 0 011.17 3.247.75.75 0 00.205.503z"/></svg>
                    </span>
                    <h3 class="mt-5 font-display text-lg font-semibold text-brand-800">Harga Bersahabat</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink/60">Harga transparan dengan pilihan paket yang memudahkan Anda merawat diri.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- GALERI --}}
    @if ($galleries->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <x-sections.section-heading title="Galeri" description="Lihat suasana dan momen perawatan di Dian Mustika.">
                Galeri
            </x-sections.section-heading>

            <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($galleries as $gallery)
                    <x-cards.gallery-item :gallery="$gallery" />
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('galleries.index') }}" class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white px-7 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">
                    Lihat Galeri Lengkap <span aria-hidden="true">→</span>
                </a>
            </div>
        </section>
    @endif

    {{-- TESTIMONIAL --}}
    @if ($testimonials->isNotEmpty())
        <section class="bg-gradient-to-b from-cream to-brand-100/40 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sections.section-heading title="Testimoni Pelanggan" description="Kepercayaan pelanggan adalah kebanggaan kami.">
                    Testimoni
                </x-sections.section-heading>

                <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials->take(3) as $testimonial)
                        <x-cards.testimonial-card :testimonial="$testimonial" />
                    @endforeach
                </div>

                <div class="mt-10 text-center">
                    <a href="{{ route('testimonials.index') }}" class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white px-7 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">
                        Lihat Semua Testimoni <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- LOKASI --}}
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <x-sections.section-heading title="Temukan Kami" description="Kunjungi cabang Dian Mustika terdekat dari lokasi Anda.">
            Lokasi
        </x-sections.section-heading>

        <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($locations as $location)
                <x-cards.location-card :location="$location" />
            @empty
                <p class="col-span-full text-center text-ink/60">Belum ada cabang.</p>
            @endforelse
        </div>
    </section>

    {{-- ARTIKEL TERBARU --}}
    @if ($articles->isNotEmpty())
        <section class="border-t border-brand-100 bg-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sections.section-heading title="Artikel Terbaru" description="Tips dan informasi seputar perawatan tubuh dan kecantikan.">
                    Blog & Tips
                </x-sections.section-heading>

                <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($articles as $article)
                        <x-cards.article-card :article="$article" />
                    @endforeach
                </div>

                <div class="mt-10 text-center">
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white px-7 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">
                        Lihat Semua Artikel <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- CTA WHATSAPP --}}
    <x-sections.cta />
</x-layouts.app>
