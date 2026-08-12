<x-layouts.admin title="Tambah Layanan">
    <x-admin.page-header title="Tambah Layanan" />

    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data" class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf

        <x-admin.select name="service_category_id" label="Kategori" help="Opsional.">
            <option value="">— Pilih Kategori —</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('service_category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </x-admin.select>
        <x-admin.input name="name" label="Nama Layanan" required />
        <x-admin.input name="slug" label="Slug" help="Kosongkan untuk dibuat otomatis dari nama." />
        <x-admin.textarea name="short_description" label="Deskripsi Singkat" rows="3" />
        <x-admin.textarea name="description" label="Deskripsi" rows="8" />
        <x-admin.textarea name="benefits" label="Manfaat" rows="4" help="Satu manfaat per baris." />
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.input name="duration" label="Durasi" help="Contoh: 60 menit." />
            <x-admin.input name="price" label="Harga" type="number" step="0.01" help="Dalam Rupiah." />
        </div>
        <x-admin.textarea name="note" label="Catatan" rows="3" />
        <x-admin.image-field name="image" label="Gambar" help="JPG, PNG, atau WebP. Maksimal 2MB." />
        <x-admin.input name="alt_text" label="Teks Alternatif Gambar" help="Untuk aksesibilitas." />
        <x-admin.checkbox name="is_featured" label="Unggulan" help="Tampilkan sebagai layanan unggulan." />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />

        <x-admin.seo-fields :seo="null" />

        <x-admin.form-actions :cancel="route('admin.services.index')" />
    </form>
</x-layouts.admin>
