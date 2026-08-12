<x-layouts.app :seo="$seo">

    <x-sections.page-hero title="Lokasi Kami" description="Kunjungi cabang Dian Mustika terdekat dari lokasi Anda." />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($locations as $location)
                <x-cards.location-card :location="$location" />
            @empty
                <p class="col-span-full py-16 text-center text-ink/60">Belum ada cabang.</p>
            @endforelse
        </div>
    </section>

    <x-sections.cta title="Kunjungi Cabang Terdekat" description="Hubungi cabang melalui WhatsApp untuk reservasi atau informasi lebih lanjut." />
</x-layouts.app>
