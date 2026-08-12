<x-layouts.admin title="Edit Layanan">
    <x-admin.page-header title="Edit Layanan" />

    <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data" class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')

        <x-admin.select name="service_category_id" label="Kategori" help="Opsional.">
            <option value="">— Pilih Kategori —</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('service_category_id', $service->service_category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </x-admin.select>
        <x-admin.input name="name" label="Nama Layanan" required :value="$service->name" />
        <x-admin.input name="slug" label="Slug" :value="$service->slug" help="Kosongkan untuk dibuat otomatis dari nama." />
        <x-admin.textarea name="short_description" label="Deskripsi Singkat" rows="3" :value="$service->short_description" />
        <x-admin.textarea name="description" label="Deskripsi" rows="8" :value="$service->description" />
        <x-admin.textarea name="benefits" label="Manfaat" rows="4" :value="implode(PHP_EOL, $service->benefits ?? [])" help="Satu manfaat per baris." />
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.input name="duration" label="Durasi" :value="$service->duration" help="Contoh: 60 menit." />
            <x-admin.input name="price" label="Harga" type="number" step="0.01" :value="$service->price" help="Dalam Rupiah." />
        </div>
        <x-admin.textarea name="note" label="Catatan" rows="3" :value="$service->note" />
        <x-admin.image-field name="image" label="Gambar" :value="$service->image" help="JPG, PNG, atau WebP. Maksimal 2MB." />
        <x-admin.input name="alt_text" label="Teks Alternatif Gambar" :value="$service->alt_text" />
        <x-admin.checkbox name="is_featured" label="Unggulan" :checked="$service->is_featured" />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="$service->is_active" />

        <x-admin.seo-fields :seo="$service?->seo ?? null" />

        <x-admin.form-actions :cancel="route('admin.services.index')" />
    </form>
</x-layouts.admin>
