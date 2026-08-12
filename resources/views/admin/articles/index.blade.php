<x-layouts.admin title="Artikel">
    <x-admin.page-header
        title="Artikel"
        description="Kelola artikel blog website."
        :buttonHref="route('admin.articles.create')"
        buttonLabel="+ Tambah Artikel"
    />

    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-ink/10 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-ink/10 text-sm">
            <thead class="bg-ink/5">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Judul</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Kategori</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Penulis</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Tanggal Terbit</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Unggulan</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-ink/60">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/10">
                @forelse ($articles as $article)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($article->featured_image)
                                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->alt_text }}" class="h-10 w-10 rounded-lg border border-ink/10 object-cover">
                                @endif
                                <span class="font-medium text-ink">{{ $article->title }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-ink/60">{{ $article->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-ink/60">{{ $article->author?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-center text-ink/60">{{ $article->published_at?->format('d M Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($article->is_featured)
                                <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">Unggulan</span>
                            @else
                                <span class="inline-flex rounded-full bg-ink/5 px-2 py-0.5 text-xs font-medium text-ink/50">Biasa</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($article->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.articles.edit', $article) }}" class="font-medium text-brand-700 hover:text-brand-800">Edit</a>
                            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="inline" onsubmit="return confirm('Hapus artikel ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-medium text-red-600 hover:text-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-ink/50">Belum ada artikel.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</x-layouts.admin>
