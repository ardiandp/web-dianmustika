@props(['seo' => null])

<div class="rounded-lg border border-ink/10 bg-white p-5">
    <h3 class="text-base font-semibold text-ink">SEO</h3>
    <p class="mt-1 text-sm text-ink/60">Kosongkan untuk menggunakan nilai default dari konten.</p>

    <div class="mt-4 space-y-4">
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
