<x-layouts.admin title="Chat AI">
    <x-admin.page-header title="Chat AI" description="Riwayat percakapan pengunjung dengan asisten AI." />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Sesi Percakapan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
            </div>
        </div>
        <div class="card-body">
            {{-- Filters --}}
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari percakapan / kata kunci">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="status" class="form-control">
                            <option value="all">Semua Status</option>
                            <option value="active" @selected(request('status') == 'active')>Aktif</option>
                            <option value="closed" @selected(request('status') == 'closed')>Ditutup</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" title="Dari tanggal">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" title="Sampai tanggal">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-primary btn-block"><i class="fas fa-filter mr-1"></i>Filter</button>
                    </div>
                </div>
            </form>

            <table id="datatable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sesi</th>
                        <th class="text-center">Pesan</th>
                        <th class="text-center">Perangkat</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aktivitas Terakhir</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sessions as $session)
                        <tr>
                            <td>#{{ $session->id }}</td>
                            <td>
                                <code>{{ $session->session_token }}</code>
                                <br>
                                <small class="text-muted">{{ $session->browser ?: 'Browser tidak diketahui' }} · {{ $session->device ?: '-' }}</small>
                            </td>
                            <td class="text-center">{{ $session->messages_count }}</td>
                            <td class="text-center">{{ ucfirst($session->device ?? '-') }}</td>
                            <td class="text-center">
                                <span class="badge badge-{{ $session->status === 'active' ? 'success' : 'secondary' }}">{{ $session->statusLabel() }}</span>
                            </td>
                            <td class="text-center">{{ $session->last_message_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.chats.show', $session) }}" class="btn btn-info btn-sm" title="Lihat Percakapan">
                                    <i class="fas fa-comments"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.chats.destroy', $session) }}" class="d-inline" onsubmit="return confirm('Hapus sesi chat ini?')">
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
                            <td colspan="7" class="text-center text-muted">Belum ada sesi percakapan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>$(function () {
        $('#datatable').DataTable({
            responsive: true,
            autoWidth: false,
            lengthChange: true,
            buttons: ["copy","csv","excel","pdf","print","colVis"],
            language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', emptyTable: 'Belum ada percakapan.', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } },
            columnDefs: [{ orderable: false, targets: [6] }],
            order: [[0, 'desc']]
        });
    });</script>
    @endpush
</x-layouts.admin>