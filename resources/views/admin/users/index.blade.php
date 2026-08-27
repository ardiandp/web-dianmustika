<x-layouts.admin title="Manajemen User">
    <x-admin.page-header
        title="Manajemen User"
        description="Kelola admin dan staff yang dapat login ke aplikasi."
        :buttonHref="route('admin.users.create')"
        buttonLabel="Tambah User"
    />

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table id="datatable" class="table table-hover text-nowrap">
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
                    @forelse ($users as $user)
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
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada user.</td>
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
