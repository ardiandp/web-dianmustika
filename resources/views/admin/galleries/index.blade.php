<x-layouts.admin title="Galeri">
    <x-admin.page-header
        title="Galeri"
        description="Kelola foto galeri untuk ditampilkan di website."
        :buttonHref="route('admin.galleries.create')"
        buttonLabel="Tambah Galeri"
    />

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table id="datatable" class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Caption</th>
                        <th>Kategori</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($galleries as $gallery)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->alt_text }}" class="img-circle" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>{{ $gallery->caption ?: '—' }}</td>
                            <td>{{ ucfirst($gallery->category) }}</td>
                            <td class="text-center">
                                @if ($gallery->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" class="d-inline" onsubmit="return confirm('Hapus foto galeri ini?')">
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
                            <td colspan="5" class="text-center text-muted">Belum ada foto galeri.</td>
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
