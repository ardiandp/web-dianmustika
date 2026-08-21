<x-layouts.admin title="Tambah Testimonial">
    <x-admin.page-header title="Tambah Testimonial" />

    <div class="card">
        <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <x-admin.input name="customer_name" label="Nama Pelanggan" required />
                <x-admin.input name="treatment" label="Treatment" help="Contoh: Massage Relaksasi." />
                <x-admin.select name="rating" label="Rating" required>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(old('rating', 5) == $i)>{{ $i }} / 5</option>
                    @endfor
                </x-admin.select>
                <x-admin.editor name="content" label="Isi Testimonial" required />
                <x-admin.image-field name="image" label="Foto" help="Opsional. Format: JPG, PNG, atau WEBP. Maksimal 2 MB." />
                <x-admin.checkbox name="is_featured" label="Tampilkan sebagai Unggulan" />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.testimonials.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
