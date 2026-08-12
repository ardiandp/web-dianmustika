<x-layouts.admin title="Tambah FAQ">
    <x-admin.page-header title="Tambah FAQ" />

    <form method="POST" action="{{ route('admin.faqs.store') }}" class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf

        <x-admin.select name="category" label="Kategori" required>
            <option value="">— Pilih —</option>
            <option value="umum" @selected(old('category') == 'umum')>Umum</option>
            <option value="layanan" @selected(old('category') == 'layanan')>Layanan</option>
            <option value="harga" @selected(old('category') == 'harga')>Harga</option>
            <option value="lokasi" @selected(old('category') == 'lokasi')>Lokasi</option>
            <option value="perawatan" @selected(old('category') == 'perawatan')>Perawatan</option>
        </x-admin.select>
        <x-admin.input name="question" label="Pertanyaan" required />
        <x-admin.textarea name="answer" label="Jawaban" rows="5" required />
        <x-admin.select name="service_id" label="Terkait Layanan" help="Opsional. Pilih layanan jika FAQ berkaitan dengan layanan tertentu.">
            <option value="">— Pilih —</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
            @endforeach
        </x-admin.select>
        <x-admin.select name="location_id" label="Terkait Lokasi" help="Opsional. Pilih lokasi jika FAQ berkaitan dengan lokasi tertentu.">
            <option value="">— Pilih —</option>
            @foreach ($locations as $location)
                <option value="{{ $location->id }}" @selected(old('location_id') == $location->id)>{{ $location->name }}</option>
            @endforeach
        </x-admin.select>
        <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />

        <x-admin.form-actions :cancel="route('admin.faqs.index')" />
    </form>
</x-layouts.admin>
