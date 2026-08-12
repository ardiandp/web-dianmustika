@php
    $seo = App\Services\SeoService::forPage([
        'title' => 'Halaman Tidak Ditemukan',
        'description' => 'Maaf, halaman yang Anda cari tidak tersedia.',
        'robots' => 'noindex, nofollow',
    ]);
@endphp

<x-layouts.app :seo="$seo">

    <section class="mx-auto max-w-3xl px-4 py-24 text-center sm:px-6 lg:px-8">
        <p class="font-display text-8xl font-semibold text-brand-200 sm:text-9xl">404</p>
        <h1 class="mt-4 font-display text-3xl font-semibold text-brand-800">Halaman Tidak Ditemukan</h1>
        <p class="mx-auto mt-4 max-w-md leading-relaxed text-ink/60">Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>
        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a href="{{ route('home') }}" class="inline-flex w-full items-center justify-center rounded-full bg-brand-700 px-7 py-3 text-sm font-semibold text-white transition hover:bg-brand-800 sm:w-auto">
                Kembali ke Beranda
            </a>
            <a href="{{ route('services.index') }}" class="inline-flex w-full items-center justify-center rounded-full border border-brand-200 bg-white px-7 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50 sm:w-auto">
                Lihat Layanan
            </a>
            <a href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya membutuhkan bantuan.') }}" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center rounded-full bg-[#25D366] px-7 py-3 text-sm font-semibold text-white transition hover:brightness-95 sm:w-auto">
                Hubungi Kami
            </a>
        </div>
    </section>
</x-layouts.app>
