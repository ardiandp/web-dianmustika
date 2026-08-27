<x-layouts.admin title="Paket / Promo">
    <x-admin.page-header
        title="Paket / Promo"
        description="Kelola paket treatment, bundling, dan promosi."
        :buttonHref="route('admin.packages.create')"
        buttonLabel="Tambah Paket"
    />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Paket / Promo</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
            </div>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Harga Promo</th>
                        <th>Periode</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packages as $package)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($package->image)
                                        <img src="{{ asset('storage/' . $package->image) }}" alt="" class="img-circle img-size-32 mr-2">
                                    @endif
                                    <div>
                                        {{ $package->name }}
                                        @if ($package->hasPromo())
                                            <br><span class="badge badge-warning">Promo Aktif</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                            <td>{{ $package->promo_price ? 'Rp ' . number_format($package->promo_price, 0, ',', '.') : '—' }}</td>
                            <td>
                                @if ($package->starts_at || $package->ends_at)
                                    {{ $package->starts_at?->format('d M Y') ?? '—' }} – {{ $package->ends_at?->format('d M Y') ?? '—' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($package->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="d-inline" onsubmit="return confirm('Hapus paket ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada paket atau promo.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Harga Promo</th>
                        <th>Periode</th>
                        <th class="text-center">Status</th>
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
        });
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });</script>
    @endpush
</x-layouts.admin>
