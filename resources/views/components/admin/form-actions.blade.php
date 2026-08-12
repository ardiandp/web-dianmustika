@props(['cancel' => ''])

<div class="flex items-center gap-3">
    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-brand-700 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
        Simpan
    </button>
    <a href="{{ $cancel }}" class="inline-flex items-center justify-center rounded-md border border-ink/10 bg-white px-4 py-2 text-sm font-medium text-ink/70 shadow-sm hover:bg-ink/5">
        Batal
    </a>
</div>
