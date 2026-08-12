<x-layouts.admin title="Tambah Lokasi">
    <x-admin.page-header title="Tambah Lokasi" />

    <form method="POST" action="{{ route('admin.locations.store') }}" enctype="multipart/form-data" class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf

        <x-admin.input name="name" label="Nama Lokasi" required help="Contoh: Bintaro, Tangerang, Balikpapan." />
        <x-admin.input name="slug" label="Slug" help="Kosongkan untuk dibuat otomatis dari nama." />
        <x-admin.textarea name="address" label="Alamat" rows="2" required />
        <x-admin.textarea name="description" label="Deskripsi" rows="5" />
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.input name="phone" label="Telepon" />
            <x-admin.input name="whatsapp" label="WhatsApp" help="Format: 628xxxxxxxxxx" />
        </div>
        <x-admin.input name="email" label="Email" type="email" />
        <x-admin.input name="google_maps_url" label="URL Google Maps" type="url" help="Tautan "Lihat di Google Maps"." />
        <x-admin.textarea name="google_maps_embed" label="Embed Google Maps" rows="5" help="Kode embed peta (iframe)." />
        <x-admin.textarea name="opening_hours" label="Jam Operasional" rows="4" help="Satu baris per hari. Format: Senin = 09.00 - 21.00" />
        <x-admin.image-field name="image" label="Foto" help="JPG, PNG, atau WebP. Maksimal 2MB." />
        <x-admin.input name="alt_text" label="Teks Alternatif Gambar" help="Untuk aksesibilitas." />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />

        <x-admin.seo-fields :seo="null" />

        <x-admin.form-actions :cancel="route('admin.locations.index')" />
    </form>
</x-layouts.admin>
