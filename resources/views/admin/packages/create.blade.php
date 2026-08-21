<x-layouts.admin title="Tambah Paket">
    <x-admin.page-header title="Tambah Paket" />

    <div class="card">
        <form method="POST" action="{{ route('admin.packages.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <x-admin.input name="name" label="Nama Paket" required />
                <x-admin.input name="slug" label="Slug" help="Kosongkan untuk dibuat otomatis dari nama." />
                <x-admin.editor name="description" label="Deskripsi" />
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="price" label="Harga Normal" type="number" step="0.01" required help="Dalam Rupiah." />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="promo_price" label="Harga Promo" type="number" step="0.01" help="Kosongkan jika tidak ada promo." />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="starts_at" label="Tanggal Mulai" type="date" />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="ends_at" label="Tanggal Berakhir" type="date" />
                    </div>
                </div>
                <x-admin.image-field name="image" label="Gambar" help="JPG, PNG, atau WebP. Maksimal 2MB." />
                <x-admin.input name="alt_text" label="Teks Alternatif Gambar" help="Untuk aksesibilitas." />
                <x-admin.checkbox name="is_featured" label="Unggulan" help="Tampilkan sebagai promo utama." />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />

                <x-admin.seo-fields :seo="null" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.packages.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
