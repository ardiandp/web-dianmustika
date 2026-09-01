<x-layouts.admin title="Dashboard">
    {{-- Period selector --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="text-muted">Periode: {{ $start->format('d M Y') }} — {{ $end->format('d M Y') }}</span>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.dashboard', ['period' => '7d']) }}" class="btn btn-sm {{ $period === '7d' ? 'btn-primary' : 'btn-outline-primary' }}">7 Hari</a>
            <a href="{{ route('admin.dashboard', ['period' => '30d']) }}" class="btn btn-sm {{ $period === '30d' ? 'btn-primary' : 'btn-outline-primary' }}">30 Hari</a>
            <a href="{{ route('admin.dashboard', ['period' => '12m']) }}" class="btn btn-sm {{ $period === '12m' ? 'btn-primary' : 'btn-outline-primary' }}">12 Bulan</a>
            <a href="{{ route('admin.dashboard', ['period' => 'year']) }}" class="btn btn-sm {{ $period === 'year' ? 'btn-primary' : 'btn-outline-primary' }}">Tahun Ini</a>
        </div>
    </div>

    {{-- Summary boxes --}}
    <div class="row">
        @php
            $boxes = [
                ['label' => 'Hari Ini', 'data' => $summary['today'], 'color' => 'info', 'icon' => 'fas fa-calendar-day'],
                ['label' => '7 Hari', 'data' => $summary['week'], 'color' => 'success', 'icon' => 'fas fa-calendar-week'],
                ['label' => '30 Hari', 'data' => $summary['month'], 'color' => 'warning', 'icon' => 'fas fa-calendar-alt'],
                ['label' => 'Tahun Ini', 'data' => $summary['year'], 'color' => 'danger', 'icon' => 'fas fa-calendar'],
            ];
        @endphp
        @foreach ($boxes as $box)
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-{{ $box['color'] }}"><i class="{{ $box['icon'] }}"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $box['label'] }}</span>
                        <span class="info-box-number">{{ number_format($box['data']['views']) }} views</span>
                        <small class="text-muted">{{ number_format($box['data']['unik']) }} unik · {{ number_format($box['data']['clicks']) }} klik</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Charts row --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Trafik — Views vs Unik vs Klik</h3>
                </div>
                <div class="card-body">
                    @if (array_sum($chart['views']) === 0 && array_sum($chart['clicks']) === 0)
                        <p class="text-center text-muted py-4">Belum ada data pada periode ini. Kunjungi halaman public untuk menghasilkan trafik.</p>
                    @else
                        <canvas id="trafficChart" height="140"></canvas>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-mobile-alt mr-1"></i> Device</h3>
                </div>
                <div class="card-body">
                    @if ($deviceStats->isEmpty())
                        <p class="text-center text-muted py-4">Belum ada data device.</p>
                    @else
                        <canvas id="deviceChart" height="200"></canvas>
                        <div class="mt-3">
                            @foreach ($deviceStats as $d)
                                <div class="d-flex justify-content-between">
                                    <span class="text-capitalize">{{ $d->device ?? 'unknown' }}</span>
                                    <span class="font-weight-bold">{{ $d->total }}</span>
                                </div>
        @endforeach
    </div>

    @can('manage-consultations')
    {{-- Consultation summary --}}
    <div class="row mt-2">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-clipboard-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Konsultasi Hari Ini</span>
                    <span class="info-box-number">{{ number_format($consultationStats['today']) }}</span>
                    <small class="text-muted"><a href="{{ route('admin.consultations.index') }}">Lihat semua</a></small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-inbox"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Baru</span>
                    <span class="info-box-number">{{ number_format($consultationStats['baru']) }}</span>
                    <small class="text-muted">Menunggu ditindaklanjuti</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-sync-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Diproses</span>
                    <span class="info-box-number">{{ number_format($consultationStats['diproses']) }}</span>
                    <small class="text-muted">Dihubungi / menunggu konfirmasi</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Selesai</span>
                    <span class="info-box-number">{{ number_format($consultationStats['selesai']) }}</span>
                    <small class="text-muted">Konsultasi selesai</small>
                </div>
            </div>
        </div>
    </div>
    @endcan
                    @endif
                </div>
            </div>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-mouse-pointer mr-1"></i> Klik per Elemen</h3>
                </div>
                <div class="card-body p-0">
                    @if ($clicksByElement->isEmpty())
                        <p class="text-center text-muted py-4">Belum ada klik.</p>
                    @else
                        <table class="table table-sm table-hover mb-0">
                            <thead><tr><th>Elemen</th><th class="text-right">Klik</th></tr></thead>
                            <tbody>
                                @foreach ($clicksByElement as $c)
                                    <tr><td><span class="badge badge-primary">{{ $c->element }}</span></td><td class="text-right">{{ $c->total }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Top pages + lokasi/browser --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-fire mr-1"></i> Top Halaman</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    @if ($topPages->isEmpty())
                        <p class="text-center text-muted py-4">Belum ada data halaman.</p>
                    @else
                        <table class="table table-hover text-nowrap">
                            <thead><tr><th>Path</th><th class="text-right">Views</th><th class="text-right">Unik</th><th class="text-right">Klik</th><th class="text-right">CTR</th></tr></thead>
                            <tbody>
                                @foreach ($topPages as $p)
                                    <tr>
                                        <td><a href="{{ url($p->path) }}" target="_blank" class="text-primary">{{ $p->path }}</a></td>
                                        <td class="text-right">{{ $p->views }}</td>
                                        <td class="text-right">{{ $p->unik }}</td>
                                        <td class="text-right">{{ $p->clicks }}</td>
                                        <td class="text-right">{{ $p->ctr }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-globe-asia mr-1"></i> Lokasi (Country/City)</h3>
                </div>
                <div class="card-body p-0">
                    @if ($countryStats->isEmpty())
                        <p class="text-center text-muted py-4">Belum ada data lokasi.</p>
                    @else
                        <table class="table table-sm table-hover mb-0">
                            <thead><tr><th>Negara / Kota</th><th class="text-right">Views</th></tr></thead>
                            <tbody>
                                @foreach ($countryStats as $c)
                                    <tr><td>{{ $c->country }}@if($c->city) <small class="text-muted">/ {{ $c->city }}</small>@endif</td><td class="text-right">{{ $c->total }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chrome mr-1"></i> Browser</h3>
                </div>
                <div class="card-body p-0">
                    @if ($browserStats->isEmpty())
                        <p class="text-center text-muted py-4">Belum ada data browser.</p>
                    @else
                        <table class="table table-sm table-hover mb-0">
                            <thead><tr><th>Browser</th><th class="text-right">Views</th></tr></thead>
                            <tbody>
                                @foreach ($browserStats as $b)
                                    <tr><td>{{ $b->browser }}</td><td class="text-right">{{ $b->total }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Existing content counts --}}
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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var trafficCtx = document.getElementById('trafficChart');
        if (trafficCtx) {
            new Chart(trafficCtx, {
                type: 'line',
                data: {
                    labels: @json($chart['labels']),
                    datasets: [
                        { label: 'Views', data: @json($chart['views']), borderColor: '#86434e', backgroundColor: 'rgba(134,67,78,0.1)', tension: 0.3, fill: true },
                        { label: 'Unik', data: @json($chart['unik']), borderColor: '#c9a45c', backgroundColor: 'rgba(201,164,92,0.1)', tension: 0.3, fill: true },
                        { label: 'Klik', data: @json($chart['clicks']), borderColor: '#6f3a43', backgroundColor: 'rgba(111,58,67,0.08)', tension: 0.3, fill: false, borderDash: [5,5] },
                    ]
                },
                options: { responsive: true, interaction: { intersect: false, mode: 'index' }, scales: { y: { beginAtZero: true } } }
            });
        }

        var deviceCtx = document.getElementById('deviceChart');
        if (deviceCtx) {
            new Chart(deviceCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($deviceStats->pluck('device')->map(fn($d) => ucfirst($d ?? 'Unknown'))->values()),
                    datasets: [{ data: @json($deviceStats->pluck('total')->values()), backgroundColor: ['#86434e','#c9a45c','#6f3a43','#a25460','#b08d45'] }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        }
    });
    </script>
    @endpush
</x-layouts.admin>
