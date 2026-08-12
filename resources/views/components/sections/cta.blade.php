@props(['title' => 'Siap Merawat Diri?', 'description' => 'Konsultasikan kebutuhan perawatan Anda dengan tim kami melalui WhatsApp. Kami siap membantu Anda menemukan perawatan yang paling sesuai.'])

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-800 via-brand-700 to-brand-900 px-6 py-14 text-center shadow-xl sm:px-12">
        <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(circle at 10% 20%, #e3c98e 0, transparent 30%), radial-gradient(circle at 90% 80%, #e3c98e 0, transparent 25%);"></div>
        <div class="relative">
            <h2 class="font-display text-3xl font-semibold text-cream sm:text-4xl">{{ $title }}</h2>
            <p class="mx-auto mt-4 max-w-2xl text-brand-100/80">{{ $description }}</p>
            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a
                    href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya ingin konsultasi perawatan.') }}"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#25D366] px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:brightness-95 sm:w-auto"
                >
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24s-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.24-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/></svg>
                    Konsultasi via WhatsApp
                </a>
                <a
                    href="{{ route('services.index') }}"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-cream/40 px-7 py-3.5 text-sm font-semibold text-cream transition hover:bg-cream/10 sm:w-auto"
                >
                    Lihat Layanan
                </a>
            </div>
        </div>
    </div>
</section>
