<x-layouts.admin title="Tambah Layanan">
    <x-admin.page-header title="Tambah Layanan" />

    <div class="card">
        <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <x-admin.select name="service_category_id" label="Kategori" help="Opsional.">
                    <option value="">— Pilih Kategori —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('service_category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </x-admin.select>
                <x-admin.input name="name" label="Nama Layanan" required />
                <x-admin.input name="slug" label="Slug" help="Kosongkan untuk dibuat otomatis dari nama." />
                <x-admin.textarea name="short_description" label="Deskripsi Singkat" rows="3" />
                <x-admin.editor name="description" label="Deskripsi" />
                <x-admin.textarea name="benefits" label="Manfaat" rows="4" help="Satu manfaat per baris." />
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="duration" label="Durasi" help="Contoh: 60 menit." />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="price" label="Harga" type="number" step="0.01" help="Dalam Rupiah." />
                    </div>
                </div>
                <x-admin.textarea name="note" label="Catatan" rows="3" />
                <x-admin.image-field name="image" label="Gambar" help="JPG, PNG, atau WebP. Maksimal 2MB." />
                <x-admin.input name="alt_text" label="Teks Alternatif Gambar" help="Untuk aksesibilitas." />
                <x-admin.checkbox name="is_featured" label="Unggulan" help="Tampilkan sebagai layanan unggulan." />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />

                <x-admin.seo-fields :seo="null" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.services.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
