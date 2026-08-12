<x-layouts.app title="Galeri" description="Lihat suasana dan momen perawatan di Dian Mustika.">

    <x-sections.page-hero title="Galeri" description="Momen dan suasana perawatan di Dian Mustika." />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-2">
            <a href="{{ route('galleries.index') }}" class="rounded-full px-5 py-2 text-sm font-semibold transition {{ $current === '' ? 'bg-brand-700 text-white' : 'bg-white text-brand-700 ring-1 ring-brand-200 hover:bg-brand-50' }}">
                Semua
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('galleries.index', ['kategori' => $category]) }}" class="rounded-full px-5 py-2 text-sm font-semibold transition {{ $current === $category ? 'bg-brand-700 text-white' : 'bg-white text-brand-700 ring-1 ring-brand-200 hover:bg-brand-50' }}">
                    {{ match ($category) {
                        'tempat' => 'Tempat',
                        'treatment' => 'Treatment',
                        'aktivitas' => 'Aktivitas',
                        'promo' => 'Promo',
                        default => ucfirst($category),
                    } }}
                </a>
            @endforeach
        </div>

        <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @forelse ($galleries as $gallery)
                <x-cards.gallery-item :gallery="$gallery" />
            @empty
                <p class="col-span-full py-16 text-center text-ink/60">Belum ada foto pada kategori ini.</p>
            @endforelse
        </div>
    </section>

    <x-sections.cta />
</x-layouts.app>
