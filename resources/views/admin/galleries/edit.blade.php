<x-layouts.admin title="Edit Galeri">
    <x-admin.page-header title="Edit Galeri" />

    <div class="card">
        <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
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
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.galleries.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
