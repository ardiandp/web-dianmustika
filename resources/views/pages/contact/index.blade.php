<x-layouts.app :seo="$seo">

    <x-sections.page-hero title="Hubungi Kami" description="Kami siap membantu Anda memilih perawatan yang tepat." />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
            <div class="space-y-5">
                <a href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya ingin konsultasi.') }}" target="_blank" rel="noopener" class="flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5 transition hover:shadow-md">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#25D366] text-white">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24s-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.24-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/></svg>
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-ink/40">WhatsApp</p>
                        <p class="font-semibold text-brand-800">{{ App\Services\WhatsAppService::display() }}</p>
                        <p class="text-xs text-ink/50">Konsultasi gratis, setiap hari</p>
                    </div>
                </a>

                @if (App\Models\Setting::get('phone'))
                    <div class="flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        </span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-ink/40">Telepon</p>
                            <p class="font-semibold text-brand-800">{{ App\Models\Setting::get('phone') }}</p>
                        </div>
                    </div>
                @endif

                @if (App\Models\Setting::get('email'))
                    <div class="flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        </span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-ink/40">Email</p>
                            <p class="font-semibold text-brand-800">{{ App\Models\Setting::get('email') }}</p>
                        </div>
                    </div>
                @endif

                @if (App\Models\Setting::get('address'))
                    <div class="flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-ink/40">Alamat</p>
                            <p class="font-semibold text-brand-800">{{ App\Models\Setting::get('address') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <h2 class="font-display text-2xl font-semibold text-brand-800">Cabang Kami</h2>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    @forelse ($locations as $location)
                        <a href="{{ route('locations.show', $location) }}" class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-ink/5 transition hover:shadow-md">
                            <div class="aspect-[16/9] overflow-hidden">
                                @if ($location->image)
                                    <img src="{{ asset('storage/'.$location->image) }}" alt="{{ $location->alt_text ?: $location->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-brand-100 font-display text-2xl text-brand-400">{{ $location->name[0] ?? 'L' }}</div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-display text-lg font-semibold text-brand-800">{{ $location->name }}</h3>
                                <p class="mt-1 text-xs leading-relaxed text-ink/60">{{ $location->address }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-ink/60">Belum ada cabang.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <x-sections.cta />
</x-layouts.app>
