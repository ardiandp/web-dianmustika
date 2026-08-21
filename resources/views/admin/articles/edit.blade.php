<x-layouts.admin title="Edit Artikel">
    <x-admin.page-header title="Edit Artikel" />

    <div class="card">
        <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <x-admin.select name="article_category_id" label="Kategori" help="Opsional.">
                    <option value="">— Pilih Kategori —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('article_category_id', $article->article_category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </x-admin.select>
                <x-admin.input name="title" label="Judul" required :value="$article->title" />
                <x-admin.input name="slug" label="Slug" :value="$article->slug" help="Kosongkan untuk dibuat otomatis dari judul." />
                <x-admin.textarea name="excerpt" label="Ringkasan" rows="3" :value="$article->excerpt" />
                <x-admin.editor name="content" label="Konten" required :value="$article->content" />
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.select name="author_id" label="Penulis" required>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" @selected(old('author_id', $article->author_id) == $author->id)>{{ $author->name }}</option>
                            @endforeach
                        </x-admin.select>
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="published_at" label="Tanggal Terbit" type="date" :value="$article->published_at?->format('Y-m-d')" help="Kosongkan untuk terbit sekarang." />
                    </div>
                </div>
                <x-admin.image-field name="featured_image" label="Gambar Utama" :value="$article->featured_image" help="JPG, PNG, atau WebP. Maksimal 2MB." />
                <x-admin.input name="alt_text" label="Teks Alternatif Gambar" :value="$article->alt_text" />
                <x-admin.checkbox name="is_featured" label="Unggulan" :checked="$article->is_featured" />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="$article->is_active" />

                <x-admin.seo-fields :seo="$article?->seo ?? null" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.articles.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
