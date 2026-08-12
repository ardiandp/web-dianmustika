<x-layouts.admin title="Edit Testimonial">
    <x-admin.page-header title="Edit Testimonial" />

    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data" class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')

        <x-admin.input name="customer_name" label="Nama Pelanggan" required :value="$testimonial->customer_name" />
        <x-admin.input name="treatment" label="Treatment" :value="$testimonial->treatment" help="Contoh: Massage Relaksasi." />
        <x-admin.select name="rating" label="Rating" required>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" @selected(old('rating', $testimonial->rating) == $i)>{{ $i }} / 5</option>
            @endfor
        </x-admin.select>
        <x-admin.textarea name="content" label="Isi Testimonial" rows="5" required :value="$testimonial->content" />
        <x-admin.image-field name="image" label="Foto" :value="$testimonial->image" help="Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG, atau WEBP. Maksimal 2 MB." />
        <x-admin.checkbox name="is_featured" label="Tampilkan sebagai Unggulan" :checked="$testimonial->is_featured" />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="$testimonial->is_active" />

        <x-admin.form-actions :cancel="route('admin.testimonials.index')" />
    </form>
</x-layouts.admin>
