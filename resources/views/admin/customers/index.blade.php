<x-layouts.admin title="Customer">
    <x-admin.page-header title="Customer" description="Kelola database customer." />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Customer</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama / WhatsApp / Instagram">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary"><i class="fas fa-search mr-1"></i>Cari</button>
                    </div>
                </div>
            </form>

            <table id="datatable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>WhatsApp</th>
                        <th>Instagram</th>
                        <th class="text-center">Konsultasi</th>
                        <th class="text-center">Kunjungan</th>
                        <th class="text-center">Bergabung</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td class="font-weight-bold">{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->instagram ?: '—' }}</td>
                            <td class="text-center">{{ $customer->consultations_count }}</td>
                            <td class="text-center">{{ $customer->treatment_visits_count }}</td>
                            <td class="text-center">{{ $customer->created_at?->format('d/m/Y') }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" class="d-inline" onsubmit="return confirm('Hapus customer ini? Data terkait juga akan dihapus.')">
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
                            <td colspan="8" class="text-center text-muted">Belum ada customer.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>WhatsApp</th>
                        <th>Instagram</th>
                        <th class="text-center">Konsultasi</th>
                        <th class="text-center">Kunjungan</th>
                        <th class="text-center">Bergabung</th>
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
            columnDefs: [{ orderable: false, targets: [7] }],
            order: [[0, 'desc']]
        });
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });</script>
    @endpush
</x-layouts.admin>
