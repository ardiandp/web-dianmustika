<x-layouts.admin title="Testimonial">
    <x-admin.page-header
        title="Testimonial"
        description="Kelola ulasan pelanggan untuk ditampilkan di website."
        :buttonHref="route('admin.testimonials.create')"
        buttonLabel="Tambah Testimonial"
    />

    @if (session('success'))
        <div class="callout callout-success">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Treatment</th>
                        <th class="text-center">Rating</th>
                        <th class="text-center">Unggulan</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonials as $testimonial)
                        <tr>
                            <td>{{ $testimonial->customer_name }}</td>
                            <td>{{ $testimonial->treatment ?: '—' }}</td>
                            <td class="text-center">{{ $testimonial->rating }} / 5</td>
                            <td class="text-center">
                                @if ($testimonial->is_featured)
                                    <span class="badge badge-info">Unggulan</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($testimonial->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" class="d-inline" onsubmit="return confirm('Hapus testimonial ini?')">
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
                            <td colspan="6" class="text-center text-muted">Belum ada testimonial.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $testimonials->links() }}
        </div>
    </div>
</x-layouts.admin>
