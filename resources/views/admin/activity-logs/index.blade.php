<x-layouts.admin title="Log Aktivitas">
    <x-admin.page-header title="Log Aktivitas" description="Riwayat perubahan, posting, hapus, serta login/logout admin. Hanya admin dapat melihat halaman ini. Retensi 6 bulan." />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row">
                <div class="col-md-2">
                    <label class="small">User</label>
                    <select name="user_id" class="form-control form-control-sm">
                        <option value="">Semua User</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small">Modul</label>
                    <select name="module" class="form-control form-control-sm">
                        <option value="">Semua Modul</option>
                        @foreach ($modules as $m)
                            <option value="{{ $m }}" @selected(request('module') == $m)>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small">Aksi</label>
                    <select name="action" class="form-control form-control-sm">
                        <option value="">Semua Aksi</option>
                        @foreach ($actions as $a)
                            <option value="{{ $a }}" @selected(request('action') == $a)>{{ ucfirst($a) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small">Dari</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="small">Sampai</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search mr-1"></i> Filter</button>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Total: {{ $logs->count() }} log | Retensi 6 bulan — log lebih lama akan dihapus otomatis.</small>
            <form method="POST" action="{{ route('admin.activity-logs.prune') }}" onsubmit="return confirm('Hapus semua log lebih dari 6 bulan?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-broom mr-1"></i> Bersihkan Log &gt;6 Bulan</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table id="datatable" class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th class="text-center">Aksi</th>
                        <th>Modul</th>
                        <th>Deskripsi</th>
                        <th>IP</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small><br><small class="text-muted">{{ $log->created_at->diffForHumans() }}</small></td>
                            <td>
                                <span class="font-weight-bold">{{ $log->user_name }}</span>
                                @if ($log->user)
                                    <br><small class="text-muted">{{ $log->user->email }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $badge = match($log->action) {
                                        'created' => 'badge-success',
                                        'updated' => 'badge-warning',
                                        'deleted' => 'badge-danger',
                                        'login' => 'badge-primary',
                                        'logout' => 'badge-secondary',
                                        'login_failed' => 'badge-dark',
                                        default => 'badge-light',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ $log->action }}</span>
                            </td>
                            <td><span class="badge badge-info">{{ $log->module }}</span></td>
                            <td>
                                <span title="{{ $log->description }}">{{ \Illuminate\Support\Str::limit($log->description, 60) }}</span>
                                @if ($log->changes)
                                    <button type="button" class="btn btn-link btn-sm p-0 ml-2" data-toggle="modal" data-target="#log-{{ $log->id }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                @endif
                                @if ($log->loggable_type)
                                    <br><small class="text-muted">{{ class_basename($log->loggable_type) }} #{{ $log->loggable_id }}</small>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('admin.activity-logs.destroy', $log) }}" class="d-inline" onsubmit="return confirm('Hapus log ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">Belum ada log aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Detail modals --}}
    @foreach ($logs as $log)
        @if ($log->changes)
            <div class="modal fade" id="log-{{ $log->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Perubahan — {{ $log->description }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted small">{{ $log->created_at->format('d/m/Y H:i:s') }} · {{ $log->user_name }} · {{ $log->action }} · {{ $log->module }}</p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead><tr><th>Field</th><th>Lama</th><th>Baru</th></tr></thead>
                                    <tbody>
                                        @foreach ($log->changes as $field => $change)
                                            @if (is_array($change) && array_key_exists('old', $change))
                                                <tr><td><code>{{ $field }}</code></td><td class="text-danger">{{ is_scalar($change['old']) ? $change['old'] : json_encode($change['old']) }}</td><td class="text-success">{{ is_scalar($change['new']) ? $change['new'] : json_encode($change['new']) }}</td></tr>
                                            @else
                                                <tr><td><code>{{ $field }}</code></td><td colspan="2">{{ is_scalar($change) ? $change : json_encode($change) }}</td></tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($log->user_agent)
                                <small class="text-muted">UA: {{ $log->user_agent }}</small>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @push('scripts')
    <script>$(function () { $('#datatable').DataTable({ order: [[0, 'desc']], language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', emptyTable: 'Belum ada log', zeroRecords: 'Data tidak ditemukan' } }); });</script>
    @endpush
</x-layouts.admin>
