<x-layouts.admin title="Tambah Kategori Layanan">
    <x-admin.page-header title="Tambah Kategori Layanan" />

    <form method="POST" action="{{ route('admin.service-categories.store') }}" class="max-w-2xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf

        <x-admin.input name="name" label="Nama" required help="Contoh: Massage, Slimming, Perawatan Tubuh." />
        <x-admin.input name="slug" label="Slug" help="Kosongkan untuk dibuat otomatis dari nama." />
        <x-admin.textarea name="description" label="Deskripsi" rows="3" />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />

        <x-admin.form-actions :cancel="route('admin.service-categories.index')" />
    </form>
</x-layouts.admin>
