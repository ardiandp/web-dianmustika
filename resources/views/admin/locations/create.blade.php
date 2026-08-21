<x-layouts.admin title="Tambah Lokasi">
    <x-admin.page-header title="Tambah Lokasi" />

    <div class="card">
        <form method="POST" action="{{ route('admin.locations.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <x-admin.input name="name" label="Nama Lokasi" required help="Contoh: Bintaro, Tangerang, Balikpapan." />
                <x-admin.input name="slug" label="Slug" help="Kosongkan untuk dibuat otomatis dari nama." />
                <x-admin.textarea name="address" label="Alamat" rows="2" required />
                <x-admin.editor name="description" label="Deskripsi" />
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="phone" label="Telepon" />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="whatsapp" label="WhatsApp" help="Format: 628xxxxxxxxxx" />
                    </div>
                </div>
                <x-admin.input name="email" label="Email" type="email" />
                <x-admin.input name="google_maps_url" label="URL Google Maps" type="url" help="Tautan &quot;Lihat di Google Maps&quot;." />
                <x-admin.textarea name="google_maps_embed" label="Embed Google Maps" rows="5" help="Kode embed peta (iframe)." />
                <x-admin.textarea name="opening_hours" label="Jam Operasional" rows="4" help="Satu baris per hari. Format: Senin = 09.00 - 21.00" />
                <x-admin.image-field name="image" label="Foto" help="JPG, PNG, atau WebP. Maksimal 2MB." />
                <x-admin.input name="alt_text" label="Teks Alternatif Gambar" help="Untuk aksesibilitas." />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />

                <x-admin.seo-fields :seo="null" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.locations.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
