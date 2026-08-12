<x-layouts.admin title="Edit Kategori Artikel">
    <x-admin.page-header title="Edit Kategori Artikel" />

    <form method="POST" action="{{ route('admin.article-categories.update', $category) }}" class="max-w-2xl space-y-5 rounded-lg border border-ink/10 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')

        <x-admin.input name="name" label="Nama" required :value="$category->name" />
        <x-admin.input name="slug" label="Slug" :value="$category->slug" help="Kosongkan untuk dibuat otomatis dari nama." />
        <x-admin.textarea name="description" label="Deskripsi" rows="3" :value="$category->description" />
        <x-admin.checkbox name="is_active" label="Aktif" :checked="$category->is_active" />

        <x-admin.form-actions :cancel="route('admin.article-categories.index')" />
    </form>
</x-layouts.admin>
