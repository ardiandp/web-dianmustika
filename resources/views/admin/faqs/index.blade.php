<x-layouts.admin title="FAQ">
    <x-admin.page-header
        title="FAQ"
        description="Kelola pertanyaan yang sering diajukan."
        :buttonHref="route('admin.faqs.create')"
        buttonLabel="Tambah FAQ"
    />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar FAQ</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
            </div>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Pertanyaan</th>
                        <th>Terkait</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($faqs as $faq)
                        <tr>
                            <td>
                                <span class="badge badge-primary">{{ ucfirst($faq->category) }}</span>
                            </td>
                            <td>{{ $faq->question }}</td>
                            <td>
                                @if ($faq->service)
                                    {{ $faq->service->name }}
                                @elseif ($faq->location)
                                    {{ $faq->location->name }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($faq->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="d-inline" onsubmit="return confirm('Hapus FAQ ini?')">
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
                        <th>Kategori</th>
                        <th>Pertanyaan</th>
                        <th>Terkait</th>
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
            language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', emptyTable: 'Belum ada FAQ.', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } },
        });
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });</script>
    @endpush
</x-layouts.admin>
