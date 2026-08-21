@props(['seo' => null])

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-search mr-1"></i> SEO</h3>
    </div>
    <div class="card-body">
        <small class="text-muted d-block mb-3">Kosongkan untuk menggunakan nilai default dari konten.</small>

        <x-admin.input
            name="seo_title"
            label="SEO Title"
            :value="$seo?->title"
            help="Disarankan 50–60 karakter."
        />
        <x-admin.textarea
            name="seo_description"
            label="Meta Description"
            rows="3"
            :value="$seo?->description"
            help="Disarankan 150–160 karakter."
        />
        <x-admin.input
            name="seo_keywords"
            label="Keywords"
            :value="$seo?->keywords"
            help="Pisahkan dengan koma."
        />
        <x-admin.input
            name="seo_canonical"
            label="Canonical URL"
            type="url"
            :value="$seo?->canonical"
        />
    </div>
</div>
