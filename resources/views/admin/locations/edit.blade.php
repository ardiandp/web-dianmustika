<x-layouts.admin title="Edit Lokasi">
    <x-admin.page-header title="Edit Lokasi" />

    @php
        $hoursText = '';
        foreach ($location->opening_hours ?? [] as $day => $time) {
            $hoursText .= $day . ' = ' . $time . "\n";
        }
    @endphp

    <form method="POST" action="{{ route('admin.locations.update', $location) }}" enctype="multipart/form-data" class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')

        <x-admin.input name="name" label="Nama Lokasi" required :value="$location->name" />
        <x-admin.input name="slug" label="Slug" :value="$location->slug" help="Kosongkan untuk dibuat otomatis dari nama." />
        <x-admin.textarea name="address" label="Alamat" rows="2" required :value="$location->address" />
        <x-admin.textarea name="description" label="Deskripsi" rows="5" :value="$location->description" />
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.input name="phone" label="Telepon" :value="$location->phone" />
            <x-admin.input name="whatsapp" label="WhatsApp" :value="$location->whatsapp" help="Format: 628xxxxxxxxxx" />
        </div>
        <x-admin.input name="email" label="Email" type="email" :value="$location->email" />
        <x-admin.input name="google_maps_url" label="URL Google Maps" type="url" :value="$location->google_maps_url" help="Tautan "Lihat di Google Maps"." />
        <x-admin.textarea name="google_maps_embed" label="Embed Google Maps" rows="5" :value="$location->google_maps_embed" help="Kode embed peta (iframe)." />
        <x-admin.textarea name="opening_hours" label="Jam Operasional" rows="4" :value="$hoursText" help="Satu baris per hari. Format: Senin = 09.00 - 21.00" />
        <x-admin.image-field name="image" label="Foto" :value="$location->image" help="JPG, PNG, atau WebP. Maksimal 2MB." />
        <x-admin.input name="alt_text" label="Teks Alternatif Gambar" :value="$location->alt_text" help="Untuk aksesibilitas." />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="$location->is_active" />

        <x-admin.seo-fields :seo="$location->seo ?? null" />

        <x-admin.form-actions :cancel="route('admin.locations.index')" />
    </form>
</x-layouts.admin>
