<x-layouts.admin title="Artikel">
    <x-admin.page-header
        title="Artikel"
        description="Kelola artikel blog website."
        :buttonHref="route('admin.articles.create')"
        buttonLabel="Tambah Artikel"
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
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th class="text-center">Tanggal Terbit</th>
                        <th class="text-center">Unggulan</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($article->featured_image)
                                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->alt_text }}" class="img-circle img-size-32 mr-2">
                                    @endif
                                    {{ $article->title }}
                                </div>
                            </td>
                            <td>{{ $article->category?->name ?? '—' }}</td>
                            <td>{{ $article->author?->name ?? '—' }}</td>
                            <td class="text-center">{{ $article->published_at?->format('d M Y') ?? '—' }}</td>
                            <td class="text-center">
                                @if ($article->is_featured)
                                    <span class="badge badge-warning">Unggulan</span>
                                @else
                                    <span class="badge badge-secondary">Biasa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($article->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="d-inline" onsubmit="return confirm('Hapus artikel ini?')">
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
                            <td colspan="7" class="text-center text-muted">Belum ada artikel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $articles->links() }}
        </div>
    </div>
</x-layouts.admin>
