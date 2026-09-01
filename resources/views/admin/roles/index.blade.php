<x-layouts.admin title="Role & Permission">
    <x-admin.page-header title="Role & Permission" description="Kelola role dan permission. Staff bisa ditambah/kurang menu via permission." :buttonHref="route('admin.roles.create')" buttonLabel="Tambah Role" />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Role</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
            </div>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-bordered table-striped table-hover">
                <thead><tr><th>Role</th><th class="text-center">Jumlah Permission</th><th class="text-center">Jumlah User</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td><span class="badge badge-primary">{{ $role->name }}</span></td>
                            <td class="text-center">{{ $role->permissions_count }}</td>
                            <td class="text-center">{{ $role->users_count }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                @if (!in_array($role->name, ['admin','staff']))
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline" onsubmit="return confirm('Hapus role ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot><tr><th>Role</th><th class="text-center">Jumlah Permission</th><th class="text-center">Jumlah User</th><th class="text-right">Aksi</th></tr></tfoot>
            </table>
        </div>
    </div>

    <div class="card card-outline card-info mt-3">
        <div class="card-header"><h3 class="card-title">Daftar Permission</h3></div>
        <div class="card-body">
            @php $perms = \Spatie\Permission\Models\Permission::orderBy('name')->pluck('name'); @endphp
            @foreach ($perms as $p)
                <span class="badge badge-secondary mr-1 mb-1">{{ $p }}</span>
            @endforeach
        </div>
    </div>

    @push('scripts')
    <script>$(function () {
        var table = $('#datatable').DataTable({
            responsive: true,
            autoWidth: false,
            lengthChange: true,
            buttons: ["copy","csv","excel","pdf","print","colVis"],
            language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', emptyTable: 'Belum ada role.', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } },
        });
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });</script>
    @endpush
</x-layouts.admin>
