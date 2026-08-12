<x-layouts.app title="Paket & Promo" description="Dapatkan harga spesial dengan paket perawatan pilihan di Dian Mustika.">

    <x-sections.page-hero title="Paket & Promo" description="Pilih paket perawatan dengan harga spesial dan manfaat maksimal." />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        @if ($featured->isNotEmpty())
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                @foreach ($featured as $package)
                    <x-cards.package-card :package="$package" />
                @endforeach
            </div>
        @endif

        @if ($rest->isNotEmpty())
            <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($rest as $package)
                    <x-cards.package-card :package="$package" />
                @endforeach
            </div>
        @endif

        @if ($packages->isEmpty())
            <p class="py-16 text-center text-ink/60">Belum ada paket promo saat ini.</p>
        @endif
    </section>

    <x-sections.cta title="Tertarik dengan Paket Kami?" description="Hubungi kami melalui WhatsApp untuk informasi lebih lanjut dan reservasi paket." />
</x-layouts.app>
