<x-layouts.admin title="Role & Permission">
    <x-admin.page-header title="Role & Permission" description="Kelola role dan permission. Staff bisa ditambah/kurang menu via permission." :buttonHref="route('admin.roles.create')" buttonLabel="Tambah Role" />

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table id="datatable" class="table table-hover text-nowrap">
                <thead><tr><th>Role</th><th class="text-center">Jumlah Permission</th><th class="text-center">Jumlah User</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($roles as $role)
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
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada role.</td></tr>
                    @endforelse
                </tbody>
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
    <script>$(function () { $('#datatable').DataTable({ language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', emptyTable: 'Belum ada data', zeroRecords: 'Data tidak ditemukan' } }); });</script>
    @endpush
</x-layouts.admin>
