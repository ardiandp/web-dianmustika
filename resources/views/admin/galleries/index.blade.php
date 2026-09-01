<x-layouts.admin title="Galeri">
    <x-admin.page-header
        title="Galeri"
        description="Kelola foto galeri untuk ditampilkan di website."
        :buttonHref="route('admin.galleries.create')"
        buttonLabel="Tambah Galeri"
    />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Galeri</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
            </div>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-bordered table-striped table-hover">
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
                    @foreach ($galleries as $gallery)
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
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Gambar</th>
                        <th>Caption</th>
                        <th>Kategori</th>
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
            language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', emptyTable: 'Belum ada foto galeri.', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } },
        });
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });</script>
    @endpush
</x-layouts.admin>
