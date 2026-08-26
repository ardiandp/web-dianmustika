<x-layouts.admin title="Paket / Promo">
    <x-admin.page-header
        title="Paket / Promo"
        description="Kelola paket treatment, bundling, dan promosi."
        :buttonHref="route('admin.packages.create')"
        buttonLabel="Tambah Paket"
    />

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table id="datatable" class="table table-hover text-nowrap">
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
            </table>
        </div>
    </div>

    @push('scripts')
    <script>$(function () { $('#datatable').DataTable({ language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', emptyTable: 'Belum ada data', zeroRecords: 'Data tidak ditemukan' } }); });</script>
    @endpush
</x-layouts.admin>
