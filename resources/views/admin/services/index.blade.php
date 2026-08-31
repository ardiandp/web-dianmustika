<x-layouts.admin title="Layanan">
    <x-admin.page-header
        title="Layanan"
        description="Kelola daftar layanan yang ditawarkan."
        :buttonHref="route('admin.services.create')"
        buttonLabel="Tambah Layanan"
    />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Layanan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
            </div>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama Layanan</th>
                        <th>Kategori</th>
                        <th class="text-right">Harga</th>
                        <th>Durasi</th>
                        <th class="text-center">Lokasi</th>
                        <th class="text-center">Unggulan</th>
                        <th class="text-center">Aktif</th>
                        <th class="text-center">SEO</th>
                        <th class="text-center">Views</th>
                        <th>Updated</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>
                                @if ($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->alt_text }}" class="img-circle img-size-32">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('services.show', $service) }}" target="_blank" class="font-weight-bold">{{ $service->name }}</a>
                                <br><small class="text-muted">{{ $service->slug }}</small>
                            </td>
                            <td>{{ $service->category?->name ?? '—' }}</td>
                            <td class="text-right">
                                @if ($service->tipe_harga === 'hubungi_kami')
                                    <span class="badge badge-info">Hubungi Kami</span>
                                @elseif ($service->displayPrice())
                                    {{ $service->displayPrice() }}
                                    @if ($service->tipe_harga === 'mulai_dari')
                                        <small class="text-muted d-block">Mulai dari</small>
                                    @elseif ($service->tipe_harga === 'per_lokasi')
                                        <small class="text-muted d-block">Per lokasi</small>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $service->duration ?: '—' }}</td>
                            <td class="text-center">
                                @if ($service->locations->isNotEmpty())
                                    <span class="badge badge-primary">{{ $service->locations->count() }} lokasi</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($service->is_featured)
                                    <span class="badge badge-warning">Unggulan</span>
                                @else
                                    <span class="badge badge-secondary">Biasa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($service->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php $seoStatus = $service->seoStatus(); @endphp
                                @if ($seoStatus === 'lengkap')
                                    <span class="badge badge-success" title="SEO Lengkap"><i class="fas fa-check-circle mr-1"></i>Lengkap</span>
                                @elseif ($seoStatus === 'sebagian')
                                    <span class="badge badge-warning" title="SEO Sebagian"><i class="fas fa-exclamation-circle mr-1"></i>Sebagian</span>
                                @else
                                    <span class="badge badge-danger" title="SEO Belum Diisi"><i class="fas fa-times-circle mr-1"></i>Belum</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ number_format($serviceViews['/layanan/' . $service->slug] ?? 0) }}</span>
                            </td>
                            <td><small class="text-muted">{{ $service->updated_at?->format('d/m/Y H:i') }}</small></td>
                            <td class="text-right">
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-info btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="d-inline" onsubmit="return confirm('Hapus layanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center text-muted">Belum ada layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama Layanan</th>
                        <th>Kategori</th>
                        <th class="text-right">Harga</th>
                        <th>Durasi</th>
                        <th class="text-center">Lokasi</th>
                        <th class="text-center">Unggulan</th>
                        <th class="text-center">Aktif</th>
                        <th class="text-center">SEO</th>
                        <th class="text-center">Views</th>
                        <th>Updated</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>$(function () {
        var table = $('#datatable').DataTable({
            responsive: true,
            autoWidth: false,
            lengthChange: true,
            buttons: ["copy","csv","excel","pdf","print","colVis"],
            language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', emptyTable: 'Belum ada data', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } },
            columnDefs: [{ orderable: false, targets: [1, 12] }],
            order: [[0, 'desc']]
        });
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });</script>
    @endpush
</x-layouts.admin>
