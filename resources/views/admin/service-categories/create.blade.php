<x-layouts.admin title="Tambah Kategori Layanan">
    <x-admin.page-header title="Tambah Kategori Layanan" />

    <div class="card">
        <form method="POST" action="{{ route('admin.service-categories.store') }}">
            @csrf
            <div class="card-body">
                <x-admin.input name="name" label="Nama" required help="Contoh: Massage, Slimming, Perawatan Tubuh." />
                <x-admin.input name="slug" label="Slug" help="Kosongkan untuk dibuat otomatis dari nama." />
                <x-admin.editor name="description" label="Deskripsi" />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.service-categories.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
