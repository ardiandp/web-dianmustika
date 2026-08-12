<x-layouts.app :seo="$seo">

    <x-sections.page-hero title="Layanan Kami" description="Pilih perawatan yang sesuai dengan kebutuhan Anda." />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        @if ($categories->isNotEmpty())
            <div class="flex flex-wrap justify-center gap-2">
                <a href="{{ route('services.index') }}" class="rounded-full px-5 py-2 text-sm font-semibold transition {{ ! request('kategori') ? 'bg-brand-700 text-white' : 'bg-white text-brand-700 ring-1 ring-brand-200 hover:bg-brand-50' }}">
                    Semua
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('services.index', ['kategori' => $category->slug]) }}" class="rounded-full px-5 py-2 text-sm font-semibold transition {{ request('kategori') === $category->slug ? 'bg-brand-700 text-white' : 'bg-white text-brand-700 ring-1 ring-brand-200 hover:bg-brand-50' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($services as $service)
                <x-cards.service-card :service="$service" />
            @empty
                <p class="col-span-full py-16 text-center text-ink/60">Belum ada layanan pada kategori ini.</p>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $services->links() }}
        </div>
    </section>

    <x-sections.cta />
</x-layouts.app>
