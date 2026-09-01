<x-layouts.admin title="Lokasi">
    <x-admin.page-header
        title="Lokasi"
        description="Kelola cabang dan alamat Dian Mustika."
        :buttonHref="route('admin.locations.create')"
        buttonLabel="Tambah Lokasi"
    />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Lokasi</h3>
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
                        <th>Alamat</th>
                        <th>WhatsApp</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locations as $location)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($location->image)
                                        <img src="{{ asset('storage/' . $location->image) }}" alt="" class="img-circle img-size-32 mr-2">
                                    @endif
                                    {{ $location->name }}
                                </div>
                            </td>
                            <td class="text-truncate" style="max-width: 250px;">{{ $location->address }}</td>
                            <td>{{ $location->whatsapp ?: '—' }}</td>
                            <td class="text-center">
                                @if ($location->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.locations.edit', $location) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" class="d-inline" onsubmit="return confirm('Hapus lokasi ini?')">
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
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>WhatsApp</th>
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
            language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', emptyTable: 'Belum ada lokasi.', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } },
        });
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });</script>
    @endpush
</x-layouts.admin>
