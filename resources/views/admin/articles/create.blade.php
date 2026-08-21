<x-layouts.admin title="Tambah Artikel">
    <x-admin.page-header title="Tambah Artikel" />

    <div class="card">
        <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <x-admin.select name="article_category_id" label="Kategori" help="Opsional.">
                    <option value="">— Pilih Kategori —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('article_category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </x-admin.select>
                <x-admin.input name="title" label="Judul" required />
                <x-admin.input name="slug" label="Slug" help="Kosongkan untuk dibuat otomatis dari judul." />
                <x-admin.textarea name="excerpt" label="Ringkasan" rows="3" />
                <x-admin.editor name="content" label="Konten" required />
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.select name="author_id" label="Penulis" required>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" @selected(old('author_id', auth()->id()) == $author->id)>{{ $author->name }}</option>
                            @endforeach
                        </x-admin.select>
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="published_at" label="Tanggal Terbit" type="date" :value="old('published_at', now()->format('Y-m-d'))" help="Kosongkan untuk terbit sekarang." />
                    </div>
                </div>
                <x-admin.image-field name="featured_image" label="Gambar Utama" help="JPG, PNG, atau WebP. Maksimal 2MB." />
                <x-admin.input name="alt_text" label="Teks Alternatif Gambar" help="Untuk aksesibilitas." />
                <x-admin.checkbox name="is_featured" label="Unggulan" help="Tampilkan sebagai artikel unggulan." />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />

                <x-admin.seo-fields :seo="null" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.articles.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
