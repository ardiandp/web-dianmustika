<x-layouts.admin title="Lokasi">
    <x-admin.page-header
        title="Lokasi"
        description="Kelola cabang dan alamat Dian Mustika."
        :buttonHref="route('admin.locations.create')"
        buttonLabel="+ Tambah Lokasi"
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
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Alamat</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">WhatsApp</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-ink/60">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/10">
                @forelse ($locations as $location)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($location->image)
                                    <img src="{{ asset('storage/' . $location->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                @endif
                                <span class="font-medium text-ink">{{ $location->name }}</span>
                            </div>
                        </td>
                        <td class="max-w-xs truncate px-4 py-3 text-ink/60">{{ $location->address }}</td>
                        <td class="px-4 py-3 text-ink/60">{{ $location->whatsapp ?: '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($location->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.locations.edit', $location) }}" class="font-medium text-brand-700 hover:text-brand-800">Edit</a>
                            <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" class="inline" onsubmit="return confirm('Hapus lokasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-medium text-red-600 hover:text-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-ink/50">Belum ada lokasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $locations->links() }}
    </div>
</x-layouts.admin>
