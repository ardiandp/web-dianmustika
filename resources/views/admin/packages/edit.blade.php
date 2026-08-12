<x-layouts.admin title="Edit Paket">
    <x-admin.page-header title="Edit Paket" />

    <form method="POST" action="{{ route('admin.packages.update', $package) }}" enctype="multipart/form-data" class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')

        <x-admin.input name="name" label="Nama Paket" required :value="$package->name" />
        <x-admin.input name="slug" label="Slug" :value="$package->slug" help="Kosongkan untuk dibuat otomatis dari nama." />
        <x-admin.textarea name="description" label="Deskripsi" rows="5" :value="$package->description" />
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.input name="price" label="Harga Normal" type="number" step="0.01" required :value="$package->price" help="Dalam Rupiah." />
            <x-admin.input name="promo_price" label="Harga Promo" type="number" step="0.01" :value="$package->promo_price" help="Kosongkan jika tidak ada promo." />
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.input name="starts_at" label="Tanggal Mulai" type="date" :value="$package->starts_at?->format('Y-m-d')" />
            <x-admin.input name="ends_at" label="Tanggal Berakhir" type="date" :value="$package->ends_at?->format('Y-m-d')" />
        </div>
        <x-admin.image-field name="image" label="Gambar" :value="$package->image" help="JPG, PNG, atau WebP. Maksimal 2MB." />
        <x-admin.input name="alt_text" label="Teks Alternatif Gambar" :value="$package->alt_text" help="Untuk aksesibilitas." />
        <x-admin.checkbox name="is_featured" label="Unggulan" :checked="$package->is_featured" help="Tampilkan sebagai promo utama." />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="$package->is_active" />

        <x-admin.seo-fields :seo="$package->seo ?? null" />

        <x-admin.form-actions :cancel="route('admin.packages.index')" />
    </form>
</x-layouts.admin>
