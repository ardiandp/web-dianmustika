<x-layouts.admin title="Edit Galeri">
    <x-admin.page-header title="Edit Galeri" />

    <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data" class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')

        <x-admin.select name="category" label="Kategori" required>
            <option value="">— Pilih —</option>
            <option value="tempat" @selected(old('category', $gallery->category) == 'tempat')>Tempat</option>
            <option value="treatment" @selected(old('category', $gallery->category) == 'treatment')>Treatment</option>
            <option value="aktivitas" @selected(old('category', $gallery->category) == 'aktivitas')>Aktivitas</option>
            <option value="promo" @selected(old('category', $gallery->category) == 'promo')>Promo</option>
        </x-admin.select>
        <x-admin.image-field name="image" label="Gambar" :value="$gallery->image" help="Kosongkan jika tidak ingin mengubah gambar. Format: JPG, PNG, atau WEBP. Maksimal 2 MB." />
        <x-admin.input name="alt_text" label="Alt Text" :value="$gallery->alt_text" help="Teks alternatif untuk gambar." />
        <x-admin.input name="caption" label="Caption" :value="$gallery->caption" help="Contoh: Ruang terapi utama." />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="$gallery->is_active" />

        <x-admin.form-actions :cancel="route('admin.galleries.index')" />
    </form>
</x-layouts.admin>
