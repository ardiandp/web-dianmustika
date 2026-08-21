<x-layouts.admin title="Edit Lokasi">
    <x-admin.page-header title="Edit Lokasi" />

    @php
        $hoursText = '';
        foreach ($location->opening_hours ?? [] as $day => $time) {
            $hoursText .= $day . ' = ' . $time . "\n";
        }
    @endphp

    <div class="card">
        <form method="POST" action="{{ route('admin.locations.update', $location) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <x-admin.input name="name" label="Nama Lokasi" required :value="$location->name" />
                <x-admin.input name="slug" label="Slug" :value="$location->slug" help="Kosongkan untuk dibuat otomatis dari nama." />
                <x-admin.textarea name="address" label="Alamat" rows="2" required :value="$location->address" />
                <x-admin.editor name="description" label="Deskripsi" :value="$location->description" />
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="phone" label="Telepon" :value="$location->phone" />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="whatsapp" label="WhatsApp" :value="$location->whatsapp" help="Format: 628xxxxxxxxxx" />
                    </div>
                </div>
                <x-admin.input name="email" label="Email" type="email" :value="$location->email" />
                <x-admin.input name="google_maps_url" label="URL Google Maps" type="url" :value="$location->google_maps_url" help="Tautan &quot;Lihat di Google Maps&quot;." />
                <x-admin.textarea name="google_maps_embed" label="Embed Google Maps" rows="5" :value="$location->google_maps_embed" help="Kode embed peta (iframe)." />
                <x-admin.textarea name="opening_hours" label="Jam Operasional" rows="4" :value="$hoursText" help="Satu baris per hari. Format: Senin = 09.00 - 21.00" />
                <x-admin.image-field name="image" label="Foto" :value="$location->image" help="JPG, PNG, atau WebP. Maksimal 2MB." />
                <x-admin.input name="alt_text" label="Teks Alternatif Gambar" :value="$location->alt_text" />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="$location->is_active" />

                <x-admin.seo-fields :seo="$location->seo ?? null" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.locations.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
