<x-layouts.admin title="Dashboard">
    <x-admin.page-header title="Dashboard" description="Ringkasan konten website Dian Mustika." />

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $stats = [
                ['label' => 'Layanan', 'count' => \App\Models\Service::count(), 'route' => 'admin.services.index'],
                ['label' => 'Kategori Layanan', 'count' => \App\Models\ServiceCategory::count(), 'route' => 'admin.service-categories.index'],
                ['label' => 'Paket / Promo', 'count' => \App\Models\Package::count(), 'route' => 'admin.packages.index'],
                ['label' => 'Lokasi', 'count' => \App\Models\Location::count(), 'route' => 'admin.locations.index'],
                ['label' => 'Galeri', 'count' => \App\Models\Gallery::count(), 'route' => 'admin.galleries.index'],
                ['label' => 'Testimonial', 'count' => \App\Models\Testimonial::count(), 'route' => 'admin.testimonials.index'],
                ['label' => 'Artikel', 'count' => \App\Models\Article::count(), 'route' => 'admin.articles.index'],
                ['label' => 'Kategori Artikel', 'count' => \App\Models\ArticleCategory::count(), 'route' => 'admin.article-categories.index'],
                ['label' => 'FAQ', 'count' => \App\Models\Faq::count(), 'route' => 'admin.faqs.index'],
            ];
        @endphp

        @foreach ($stats as $stat)
            <a
                href="{{ route($stat['route']) }}"
                class="rounded-lg border border-ink/10 bg-white p-5 shadow-sm transition hover:border-brand-300 hover:shadow"
            >
                <p class="text-sm font-medium text-ink/60">{{ $stat['label'] }}</p>
                <p class="mt-2 text-3xl font-semibold text-brand-800">{{ $stat['count'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-6 rounded-lg border border-ink/10 bg-white p-5">
        <h2 class="text-base font-semibold text-ink">Pengaturan Website</h2>
        <p class="mt-1 text-sm text-ink/60">
            Kelola nama website, WhatsApp, telepon, email, alamat, jam operasional, dan media sosial.
        </p>
        <a href="{{ route('admin.settings.edit') }}" class="mt-3 inline-flex text-sm font-medium text-brand-700 hover:text-brand-800">
            Kelola Pengaturan →
        </a>
    </div>
</x-layouts.admin>
