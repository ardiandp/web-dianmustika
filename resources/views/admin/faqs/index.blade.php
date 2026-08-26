<x-layouts.admin title="FAQ">
    <x-admin.page-header
        title="FAQ"
        description="Kelola pertanyaan yang sering diajukan."
        :buttonHref="route('admin.faqs.create')"
        buttonLabel="Tambah FAQ"
    />

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table id="datatable" class="table table-hover text-nowrap">
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
                    @forelse ($faqs as $faq)
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
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada FAQ.</td>
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
