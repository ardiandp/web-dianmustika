<x-layouts.admin title="Edit Paket">
    <x-admin.page-header title="Edit Paket" />

    <div class="card">
        <form method="POST" action="{{ route('admin.packages.update', $package) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <x-admin.input name="name" label="Nama Paket" required :value="$package->name" />
                <x-admin.input name="slug" label="Slug" :value="$package->slug" help="Kosongkan untuk dibuat otomatis dari nama." />
                <x-admin.editor name="description" label="Deskripsi" :value="$package->description" />
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="price" label="Harga Normal" type="number" step="0.01" required :value="$package->price" help="Dalam Rupiah." />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="promo_price" label="Harga Promo" type="number" step="0.01" :value="$package->promo_price" help="Kosongkan jika tidak ada promo." />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="starts_at" label="Tanggal Mulai" type="date" :value="$package->starts_at?->format('Y-m-d')" />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="ends_at" label="Tanggal Berakhir" type="date" :value="$package->ends_at?->format('Y-m-d')" />
                    </div>
                </div>
                <x-admin.image-field name="image" label="Gambar" :value="$package->image" help="JPG, PNG, atau WebP. Maksimal 2MB." />
                <x-admin.input name="alt_text" label="Teks Alternatif Gambar" :value="$package->alt_text" help="Untuk aksesibilitas." />
                <x-admin.checkbox name="is_featured" label="Unggulan" :checked="$package->is_featured" help="Tampilkan sebagai promo utama." />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="$package->is_active" />

                <x-admin.seo-fields :seo="$package->seo ?? null" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.packages.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
