<x-layouts.admin title="Edit Testimonial">
    <x-admin.page-header title="Edit Testimonial" />

    <div class="card">
        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <x-admin.input name="customer_name" label="Nama Pelanggan" required :value="$testimonial->customer_name" />
                <x-admin.input name="treatment" label="Treatment" :value="$testimonial->treatment" help="Contoh: Massage Relaksasi." />
                <x-admin.select name="rating" label="Rating" required>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(old('rating', $testimonial->rating) == $i)>{{ $i }} / 5</option>
                    @endfor
                </x-admin.select>
                <x-admin.editor name="content" label="Isi Testimonial" required :value="$testimonial->content" />
                <x-admin.image-field name="image" label="Foto" :value="$testimonial->image" help="Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG, atau WEBP. Maksimal 2 MB." />
                <x-admin.checkbox name="is_featured" label="Tampilkan sebagai Unggulan" :checked="$testimonial->is_featured" />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="$testimonial->is_active" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.testimonials.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
