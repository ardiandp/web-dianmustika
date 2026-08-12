<x-layouts.admin title="FAQ">
    <x-admin.page-header
        title="FAQ"
        description="Kelola pertanyaan yang sering diajukan."
        :buttonHref="route('admin.faqs.create')"
        buttonLabel="+ Tambah FAQ"
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
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Kategori</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Pertanyaan</th>
                    <th class="px-4 py-3 text-left font-medium text-ink/60">Terkait</th>
                    <th class="px-4 py-3 text-center font-medium text-ink/60">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-ink/60">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/10">
                @forelse ($faqs as $faq)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-brand-100 px-2 py-0.5 text-xs font-medium text-brand-700">{{ ucfirst($faq->category) }}</span>
                        </td>
                        <td class="px-4 py-3 font-medium text-ink">{{ $faq->question }}</td>
                        <td class="px-4 py-3 text-ink/60">
                            @if ($faq->service)
                                {{ $faq->service->name }}
                            @elseif ($faq->location)
                                {{ $faq->location->name }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($faq->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="font-medium text-brand-700 hover:text-brand-800">Edit</a>
                            <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="inline" onsubmit="return confirm('Hapus FAQ ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-medium text-red-600 hover:text-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-ink/50">Belum ada FAQ.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $faqs->links() }}
    </div>
</x-layouts.admin>
