<x-layouts.admin title="Lokasi">
    <x-admin.page-header
        title="Lokasi"
        description="Kelola cabang dan alamat Dian Mustika."
        :buttonHref="route('admin.locations.create')"
        buttonLabel="Tambah Lokasi"
    />

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table id="datatable" class="table table-hover text-nowrap">
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
                    @forelse ($locations as $location)
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
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada lokasi.</td>
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
