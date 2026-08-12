<x-layouts.admin title="Galeri">
    <x-admin.page-header
        title="Galeri"
        description="Kelola foto galeri untuk ditampilkan di website."
        :buttonHref="route('admin.galleries.create')"
        buttonLabel="+ Tambah Galeri"
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
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Gambar</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Caption</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Kategori</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-ink/60">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/10">
                @forelse ($galleries as $gallery)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->alt_text }}" class="h-12 w-16 rounded object-cover">
                        </td>
                        <td class="px-4 py-3 text-ink">{{ $gallery->caption ?: '—' }}</td>
                        <td class="px-4 py-3 text-ink/60">{{ ucfirst($gallery->category) }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($gallery->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="font-medium text-brand-700 hover:text-brand-800">Edit</a>
                            <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" class="inline" onsubmit="return confirm('Hapus foto galeri ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-medium text-red-600 hover:text-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-ink/50">Belum ada foto galeri.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $galleries->links() }}
    </div>
</x-layouts.admin>
