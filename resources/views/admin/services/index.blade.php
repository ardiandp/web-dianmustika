<x-layouts.admin title="Layanan">
    <x-admin.page-header
        title="Layanan"
        description="Kelola daftar layanan yang ditawarkan."
        :buttonHref="route('admin.services.create')"
        buttonLabel="Tambah Layanan"
    />

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table id="datatable" class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th class="text-right">Harga</th>
                        <th class="text-center">Unggulan</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($service->image)
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->alt_text }}" class="img-circle img-size-32 mr-2">
                                    @endif
                                    <span>{{ $service->name }}</span>
                                </div>
                            </td>
                            <td>{{ $service->category?->name ?? '—' }}</td>
                            <td class="text-right">{{ $service->price !== null ? 'Rp ' . number_format((float) $service->price, 0, ',', '.') : '—' }}</td>
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
                            <td class="text-right">
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="d-inline" onsubmit="return confirm('Hapus layanan ini?')">
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
                            <td colspan="6" class="text-center text-muted">Belum ada layanan.</td>
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
