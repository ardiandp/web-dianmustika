<x-layouts.admin title="Manajemen User">
    <x-admin.page-header
        title="Manajemen User"
        description="Kelola admin dan staff yang dapat login ke aplikasi."
        :buttonHref="route('admin.users.create')"
        buttonLabel="Tambah User"
    />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar User</h3>
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
                        <th>Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if ($user->id === auth()->id())
                                    <span class="badge badge-info ml-1">Anda</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                @foreach ($user->getRoleNames() as $rn)
                                    <span class="badge {{ $rn === 'admin' ? 'badge-primary' : 'badge-secondary' }} mr-1">{{ $rn }}</span>
                                @endforeach
                                @if ($user->getRoleNames()->isEmpty())
                                    @if ($user->role === 'admin')
                                        <span class="badge badge-primary">Admin</span>
                                    @else
                                        <span class="badge badge-secondary">Staff</span>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($user->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="text-center">Role</th>
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
            language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', emptyTable: 'Belum ada user.', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } },
        });
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });</script>
    @endpush
</x-layouts.admin>
