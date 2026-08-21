<x-layouts.admin title="Edit Kategori Artikel">
    <x-admin.page-header title="Edit Kategori Artikel" />

    <div class="card">
        <form method="POST" action="{{ route('admin.article-categories.update', $category) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <x-admin.input name="name" label="Nama" required :value="$category->name" />
                <x-admin.input name="slug" label="Slug" :value="$category->slug" help="Kosongkan untuk dibuat otomatis dari nama." />
                <x-admin.editor name="description" label="Deskripsi" :value="$category->description" />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="$category->is_active" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.article-categories.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
