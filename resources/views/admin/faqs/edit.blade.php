<x-layouts.admin title="Edit FAQ">
    <x-admin.page-header title="Edit FAQ" />

    <div class="card">
        <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <x-admin.select name="category" label="Kategori" required>
                    <option value="">— Pilih —</option>
                    <option value="umum" @selected(old('category', $faq->category) == 'umum')>Umum</option>
                    <option value="layanan" @selected(old('category', $faq->category) == 'layanan')>Layanan</option>
                    <option value="harga" @selected(old('category', $faq->category) == 'harga')>Harga</option>
                    <option value="lokasi" @selected(old('category', $faq->category) == 'lokasi')>Lokasi</option>
                    <option value="perawatan" @selected(old('category', $faq->category) == 'perawatan')>Perawatan</option>
                </x-admin.select>
                <x-admin.input name="question" label="Pertanyaan" required :value="$faq->question" />
                <x-admin.editor name="answer" label="Jawaban" required :value="$faq->answer" />
                <x-admin.select name="service_id" label="Terkait Layanan" help="Opsional. Pilih layanan jika FAQ berkaitan dengan layanan tertentu.">
                    <option value="">— Pilih —</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected(old('service_id', $faq->service_id ?? '') == $service->id)>{{ $service->name }}</option>
                    @endforeach
                </x-admin.select>
                <x-admin.select name="location_id" label="Terkait Lokasi" help="Opsional. Pilih lokasi jika FAQ berkaitan dengan lokasi tertentu.">
                    <option value="">— Pilih —</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" @selected(old('location_id', $faq->location_id ?? '') == $location->id)>{{ $location->name }}</option>
                    @endforeach
                </x-admin.select>
                <x-admin.checkbox name="is_active" label="Aktif" :checked="$faq->is_active" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.faqs.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
