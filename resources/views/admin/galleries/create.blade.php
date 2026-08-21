<x-layouts.admin title="Tambah Galeri">
    <x-admin.page-header title="Tambah Galeri" />

    <div class="card">
        <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <x-admin.select name="category" label="Kategori" required>
                    <option value="">— Pilih —</option>
                    <option value="tempat" @selected(old('category') == 'tempat')>Tempat</option>
                    <option value="treatment" @selected(old('category') == 'treatment')>Treatment</option>
                    <option value="aktivitas" @selected(old('category') == 'aktivitas')>Aktivitas</option>
                    <option value="promo" @selected(old('category') == 'promo')>Promo</option>
                </x-admin.select>
                <x-admin.image-field name="image" label="Gambar" required help="Format: JPG, PNG, atau WEBP. Maksimal 2 MB." />
                <x-admin.input name="alt_text" label="Alt Text" help="Teks alternatif untuk gambar." />
                <x-admin.input name="caption" label="Caption" help="Contoh: Ruang terapi utama." />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.galleries.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
