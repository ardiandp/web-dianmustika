<x-layouts.admin title="Pengaturan Website">
    <x-admin.page-header title="Pengaturan Website" description="Informasi umum dan kontak yang tampil di seluruh website." />

    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @php
        $hours = json_decode((string) $settings->get('opening_hours'), true) ?: [];
        $hoursText = '';
        foreach ($hours as $day => $time) {
            $hoursText .= $day . ' = ' . $time . "\n";
        }
    @endphp

    <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')

        <h2 class="text-base font-semibold text-ink">Informasi Umum</h2>
        <x-admin.input name="site_name" label="Nama Website" required :value="$settings->get('site_name')" />
        <x-admin.input name="site_tagline" label="Tagline" :value="$settings->get('site_tagline')" />
        <x-admin.textarea name="site_description" label="Deskripsi Website" rows="3" :value="$settings->get('site_description')" />

        <h2 class="pt-2 text-base font-semibold text-ink">Kontak</h2>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.input name="whatsapp" label="WhatsApp" :value="$settings->get('whatsapp')" help="Format: 628xxxxxxxxxx" />
            <x-admin.input name="phone" label="Telepon" :value="$settings->get('phone')" />
        </div>
        <x-admin.input name="email" label="Email" type="email" :value="$settings->get('email')" />
        <x-admin.textarea name="address" label="Alamat" rows="2" :value="$settings->get('address')" />

        <h2 class="pt-2 text-base font-semibold text-ink">Jam Operasional</h2>
        <x-admin.textarea name="opening_hours" label="Jam Operasional" rows="4" :value="$hoursText" help="Satu baris per hari. Format: Senin = 09.00 - 21.00" />

        <h2 class="pt-2 text-base font-semibold text-ink">Media Sosial</h2>
        <x-admin.input name="social_instagram" label="Instagram" type="url" :value="$settings->get('social_instagram')" />
        <x-admin.input name="social_facebook" label="Facebook" type="url" :value="$settings->get('social_facebook')" />
        <x-admin.input name="social_tiktok" label="TikTok" type="url" :value="$settings->get('social_tiktok')" />

        <h2 class="pt-2 text-base font-semibold text-ink">Lainnya</h2>
        <x-admin.textarea name="google_maps_embed" label="Embed Google Maps" rows="5" :value="$settings->get('google_maps_embed')" help="Kode embed peta (iframe)." />
        <x-admin.input name="footer_copyright" label="Teks Copyright" :value="$settings->get('footer_copyright')" />

        <x-admin.form-actions :cancel="route('admin.dashboard')" />
    </form>
</x-layouts.admin>
