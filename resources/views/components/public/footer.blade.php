@php
    $services = App\Models\Service::active()->ordered()->limit(5)->get();
    $locations = App\Models\Location::active()->ordered()->get();
    $settings = [
        'whatsapp' => App\Models\Setting::get('whatsapp'),
        'phone' => App\Models\Setting::get('phone'),
        'email' => App\Models\Setting::get('email'),
        'address' => App\Models\Setting::get('address'),
        'instagram' => App\Models\Setting::get('social_instagram'),
        'facebook' => App\Models\Setting::get('social_facebook'),
        'tiktok' => App\Models\Setting::get('social_tiktok'),
        'description' => App\Models\Setting::get('site_description'),
        'copyright' => App\Models\Setting::get('footer_copyright', '© '.date('Y').' Dian Mustika. Seluruh hak cipta dilindungi.'),
    ];
@endphp

<footer class="mt-20 bg-brand-950 text-brand-100/80">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <div class="flex items-center gap-2.5">
                    @php $logo = App\Models\Setting::get('logo'); @endphp
                    @if ($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ config('app.name') }}" class="h-10 w-auto rounded-full object-contain">
                    @else
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-brand-500 to-brand-700 font-display text-xl font-semibold text-cream">
                            D
                        </span>
                    @endif
                    <span class="font-display text-xl font-semibold text-cream">{{ App\Models\Setting::get('site_name', 'Dian Mustika') }}</span>
                </div>
                <p class="mt-4 text-sm leading-relaxed text-brand-100/60">
                    {{ $settings['description'] }}
                </p>
                <div class="mt-5 flex gap-3">
                    @if ($settings['instagram'])
                        <a href="{{ $settings['instagram'] }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-cream transition hover:bg-gold-500 hover:text-brand-950" aria-label="Instagram">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-3.26-.15-4.77-1.7-4.92-4.92C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85C2.38 3.92 3.9 2.38 7.15 2.23 8.42 2.17 8.8 2.16 12 2.16zm0 3.68a6.16 6.16 0 1 0 0 12.32 6.16 6.16 0 0 0 0-12.32zm0 10.16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.4-11.85a1.44 1.44 0 1 0 0 2.88 1.44 1.44 0 0 0 0-2.88z"/></svg>
                        </a>
                    @endif
                    @if ($settings['facebook'])
                        <a href="{{ $settings['facebook'] }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-cream transition hover:bg-gold-500 hover:text-brand-950" aria-label="Facebook">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.09 10.13 24v-8.44H7.08v-3.49h3.04V9.41c0-3.02 1.8-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.5c-1.5 0-1.96.93-1.96 1.89v2.26h3.32l-.53 3.49h-2.8V24C19.61 23.09 24 18.1 24 12.07z"/></svg>
                        </a>
                    @endif
                    @if ($settings['tiktok'])
                        <a href="{{ $settings['tiktok'] }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-cream transition hover:bg-gold-500 hover:text-brand-950" aria-label="TikTok">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="font-display text-lg font-semibold text-cream">Navigasi</h3>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}" class="transition hover:text-gold-400">Beranda</a></li>
                    <li><a href="{{ route('about') }}" class="transition hover:text-gold-400">Tentang Kami</a></li>
                    <li><a href="{{ route('services.index') }}" class="transition hover:text-gold-400">Layanan</a></li>
                    <li><a href="{{ route('packages.index') }}" class="transition hover:text-gold-400">Paket & Promo</a></li>
                    <li><a href="{{ route('galleries.index') }}" class="transition hover:text-gold-400">Galeri</a></li>
                    <li><a href="{{ route('testimonials.index') }}" class="transition hover:text-gold-400">Testimoni</a></li>
                    <li><a href="{{ route('articles.index') }}" class="transition hover:text-gold-400">Artikel</a></li>
                    <li><a href="{{ route('faqs.index') }}" class="transition hover:text-gold-400">FAQ</a></li>
                    <li><a href="{{ route('contact.index') }}" class="transition hover:text-gold-400">Kontak</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-display text-lg font-semibold text-cream">Layanan Populer</h3>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @forelse ($services as $service)
                        <li>
                            <a href="{{ route('services.show', $service) }}" class="transition hover:text-gold-400">{{ $service->name }}</a>
                        </li>
                    @empty
                        <li class="text-brand-100/60">Belum ada layanan.</li>
                    @endforelse
                </ul>
            </div>

            <div>
                <h3 class="font-display text-lg font-semibold text-cream">Kontak</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li class="flex items-start gap-2.5">
                        <span class="mt-0.5 text-gold-500">●</span>
                        <a href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika!') }}" target="_blank" rel="noopener" data-track-click="whatsapp_footer" data-track-label="Footer WhatsApp" class="transition hover:text-gold-400">
                            {{ App\Services\WhatsAppService::display() }}
                        </a>
                    </li>
                    @if ($settings['phone'])
                        <li class="flex items-start gap-2.5"><span class="mt-0.5 text-gold-500">●</span><span>{{ $settings['phone'] }}</span></li>
                    @endif
                    @if ($settings['email'])
                        <li class="flex items-start gap-2.5"><span class="mt-0.5 text-gold-500">●</span><a href="mailto:{{ $settings['email'] }}" class="transition hover:text-gold-400">{{ $settings['email'] }}</a></li>
                    @endif
                    @if ($settings['address'])
                        <li class="flex items-start gap-2.5"><span class="mt-0.5 text-gold-500">●</span><span>{{ $settings['address'] }}</span></li>
                    @endif
                </ul>

                <div class="mt-5 border-t border-white/10 pt-4">
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-brand-100/50">Cabang Kami</h4>
                    <ul class="mt-2 space-y-1.5 text-sm">
                        @forelse ($locations as $location)
                            <li><a href="{{ route('locations.show', $location) }}" class="transition hover:text-gold-400">{{ $location->name }}</a></li>
                        @empty
                            <li class="text-brand-100/60">Belum ada cabang.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-white/10 pt-6 text-xs text-brand-100/50 sm:flex-row">
            <p>{{ $settings['copyright'] }}</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="transition hover:text-gold-400">Login Admin</a>
            </div>
        </div>
    </div>
</footer>
