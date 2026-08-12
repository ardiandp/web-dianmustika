<x-layouts.admin title="Tambah Kategori Artikel">
    <x-admin.page-header title="Tambah Kategori Artikel" />

    <form method="POST" action="{{ route('admin.article-categories.store') }}" class="max-w-2xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf

        <x-admin.input name="name" label="Nama" required help="Contoh: Tips Kesehatan, Promo." />
        <x-admin.input name="slug" label="Slug" help="Kosongkan untuk dibuat otomatis dari nama." />
        <x-admin.textarea name="description" label="Deskripsi" rows="3" />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />

        <x-admin.form-actions :cancel="route('admin.article-categories.index')" />
    </form>
</x-layouts.admin>
