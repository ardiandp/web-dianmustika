<x-layouts.admin title="Konsultasi">
    <x-admin.page-header title="Konsultasi" description="Kelola konsultasi masuk dari customer." />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Konsultasi</h3>
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
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama / WhatsApp / Instagram">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="status" class="form-control">
                            <option value="all">Semua Status</option>
                            @foreach (\App\Models\Consultation::STATUS as $key => $label)
                                <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
                            @endforeach
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
                        <th>Nama</th>
                        <th>WhatsApp</th>
                        <th class="text-center">Tgl Kirim</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Kunjungan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($consultations as $consultation)
                        <tr>
                            <td>#{{ $consultation->id }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $consultation->customer_id) }}" class="font-weight-bold">{{ $consultation->customer->name }}</a>
                            </td>
                            <td>{{ $consultation->customer->phone }}</td>
                            <td class="text-center">{{ $consultation->submitted_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                @php
                                    $color = match($consultation->status) {
                                        'baru' => 'danger',
                                        'dihubungi' => 'warning',
                                        'menunggu_konfirmasi' => 'info',
                                        'booking' => 'primary',
                                        'treatment_berlangsung' => 'secondary',
                                        'selesai' => 'success',
                                        'follow_up' => 'info',
                                        'dibatalkan' => 'dark',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $color }}">{{ $consultation->statusLabel() }}</span>
                            </td>
                            <td class="text-center">{{ $consultation->treatment_visits_count }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.consultations.show', $consultation) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.consultations.destroy', $consultation) }}" class="d-inline" onsubmit="return confirm('Hapus konsultasi ini?')">
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
                            <td colspan="7" class="text-center text-muted">Belum ada konsultasi.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>WhatsApp</th>
                        <th class="text-center">Tgl Kirim</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Kunjungan</th>
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
            columnDefs: [{ orderable: false, targets: [6] }],
            order: [[0, 'desc']]
        });
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });</script>
    @endpush
</x-layouts.admin>
