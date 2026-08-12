<x-layouts.admin title="Tambah Paket">
    <x-admin.page-header title="Tambah Paket" />

    <form method="POST" action="{{ route('admin.packages.store') }}" enctype="multipart/form-data" class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf

        <x-admin.input name="name" label="Nama Paket" required />
        <x-admin.input name="slug" label="Slug" help="Kosongkan untuk dibuat otomatis dari nama." />
        <x-admin.textarea name="description" label="Deskripsi" rows="5" />
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.input name="price" label="Harga Normal" type="number" step="0.01" required help="Dalam Rupiah." />
            <x-admin.input name="promo_price" label="Harga Promo" type="number" step="0.01" help="Kosongkan jika tidak ada promo." />
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.input name="starts_at" label="Tanggal Mulai" type="date" />
            <x-admin.input name="ends_at" label="Tanggal Berakhir" type="date" />
        </div>
        <x-admin.image-field name="image" label="Gambar" help="JPG, PNG, atau WebP. Maksimal 2MB." />
        <x-admin.input name="alt_text" label="Teks Alternatif Gambar" help="Untuk aksesibilitas." />
        <x-admin.checkbox name="is_featured" label="Unggulan" help="Tampilkan sebagai promo utama." />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />

        <x-admin.seo-fields :seo="null" />

        <x-admin.form-actions :cancel="route('admin.packages.index')" />
    </form>
</x-layouts.admin>
