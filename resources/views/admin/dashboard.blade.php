<x-layouts.admin title="Dashboard">
    @php
        $stats = [
            ['label' => 'Layanan', 'count' => \App\Models\Service::count(), 'route' => 'admin.services.index', 'icon' => 'fas fa-concierge-bell', 'color' => 'info'],
            ['label' => 'Kategori Layanan', 'count' => \App\Models\ServiceCategory::count(), 'route' => 'admin.service-categories.index', 'icon' => 'fas fa-tags', 'color' => 'secondary'],
            ['label' => 'Paket / Promo', 'count' => \App\Models\Package::count(), 'route' => 'admin.packages.index', 'icon' => 'fas fa-box-open', 'color' => 'warning'],
            ['label' => 'Lokasi', 'count' => \App\Models\Location::count(), 'route' => 'admin.locations.index', 'icon' => 'fas fa-map-marker-alt', 'color' => 'danger'],
            ['label' => 'Galeri', 'count' => \App\Models\Gallery::count(), 'route' => 'admin.galleries.index', 'icon' => 'fas fa-images', 'color' => 'success'],
            ['label' => 'Testimonial', 'count' => \App\Models\Testimonial::count(), 'route' => 'admin.testimonials.index', 'icon' => 'fas fa-comment-dots', 'color' => 'primary'],
            ['label' => 'Artikel', 'count' => \App\Models\Article::count(), 'route' => 'admin.articles.index', 'icon' => 'fas fa-newspaper', 'color' => 'info'],
            ['label' => 'Kategori Artikel', 'count' => \App\Models\ArticleCategory::count(), 'route' => 'admin.article-categories.index', 'icon' => 'fas fa-folder-open', 'color' => 'secondary'],
            ['label' => 'FAQ', 'count' => \App\Models\Faq::count(), 'route' => 'admin.faqs.index', 'icon' => 'fas fa-question-circle', 'color' => 'dark'],
        ];
    @endphp

    <div class="row">
        @foreach ($stats as $stat)
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="{{ route($stat['route']) }}" class="info-box">
                    <span class="info-box-icon bg-{{ $stat['color'] }}">
                        <i class="{{ $stat['icon'] }}"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $stat['label'] }}</span>
                        <span class="info-box-number">{{ $stat['count'] }}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cog mr-1"></i> Pengaturan Website</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Kelola nama website, WhatsApp, telepon, email, alamat, jam operasional, dan media sosial.</p>
                    <a href="{{ route('admin.settings.edit') }}" class="btn btn-primary">
                        <i class="fas fa-cog mr-1"></i> Kelola Pengaturan
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
