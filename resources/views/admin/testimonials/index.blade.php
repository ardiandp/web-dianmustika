<x-layouts.admin title="Testimonial">
    <x-admin.page-header
        title="Testimonial"
        description="Kelola ulasan pelanggan untuk ditampilkan di website."
        :buttonHref="route('admin.testimonials.create')"
        buttonLabel="+ Tambah Testimonial"
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
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Nama Pelanggan</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Treatment</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Rating</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Unggulan</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-ink/60">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/10">
                @forelse ($testimonials as $testimonial)
                    <tr>
                        <td class="px-4 py-3 font-medium text-ink">{{ $testimonial->customer_name }}</td>
                        <td class="px-4 py-3 text-ink/60">{{ $testimonial->treatment ?: '—' }}</td>
                        <td class="px-4 py-3 text-center text-ink/60">{{ $testimonial->rating }} / 5</td>
                        <td class="px-4 py-3 text-center">
                            @if ($testimonial->is_featured)
                                <span class="inline-flex rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">Unggulan</span>
                            @else
                                <span class="text-ink/40">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($testimonial->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="font-medium text-brand-700 hover:text-brand-800">Edit</a>
                            <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" class="inline" onsubmit="return confirm('Hapus testimonial ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-medium text-red-600 hover:text-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-ink/50">Belum ada testimonial.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $testimonials->links() }}
    </div>
</x-layouts.admin>
