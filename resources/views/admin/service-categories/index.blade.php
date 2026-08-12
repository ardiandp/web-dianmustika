<x-layouts.admin title="Kategori Layanan">
    <x-admin.page-header
        title="Kategori Layanan"
        description="Kelompokkan layanan untuk memudahkan navigasi."
        :buttonHref="route('admin.service-categories.create')"
        buttonLabel="+ Tambah Kategori"
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
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Slug</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Jumlah Layanan</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-ink/60">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/10">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3 font-medium text-ink">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-ink/60">{{ $category->slug }}</td>
                        <td class="px-4 py-3 text-center text-ink/60">{{ $category->services_count }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($category->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.service-categories.edit', $category) }}" class="font-medium text-brand-700 hover:text-brand-800">Edit</a>
                            <form method="POST" action="{{ route('admin.service-categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-medium text-red-600 hover:text-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-ink/50">Belum ada kategori layanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</x-layouts.admin>
