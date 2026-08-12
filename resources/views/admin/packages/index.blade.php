<x-layouts.admin title="Paket / Promo">
    <x-admin.page-header
        title="Paket / Promo"
        description="Kelola paket treatment, bundling, dan promosi."
        :buttonHref="route('admin.packages.create')"
        buttonLabel="+ Tambah Paket"
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
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Harga</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Harga Promo</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Periode</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-ink/60">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/10">
                @forelse ($packages as $package)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($package->image)
                                    <img src="{{ asset('storage/' . $package->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                @endif
                                <div>
                                    <p class="font-medium text-ink">{{ $package->name }}</p>
                                    @if ($package->hasPromo())
                                        <span class="inline-flex rounded-full bg-gold-100 px-2 py-0.5 text-xs font-medium text-gold-700">Promo Aktif</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-ink/60">Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-ink/60">
                            {{ $package->promo_price ? 'Rp ' . number_format($package->promo_price, 0, ',', '.') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-ink/60">
                            @if ($package->starts_at || $package->ends_at)
                                {{ $package->starts_at?->format('d M Y') ?? '—' }} – {{ $package->ends_at?->format('d M Y') ?? '—' }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($package->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.packages.edit', $package) }}" class="font-medium text-brand-700 hover:text-brand-800">Edit</a>
                            <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="inline" onsubmit="return confirm('Hapus paket ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-medium text-red-600 hover:text-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-ink/50">Belum ada paket atau promo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $packages->links() }}
    </div>
</x-layouts.admin>
