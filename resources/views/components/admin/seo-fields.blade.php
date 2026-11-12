@props(['seo' => null])

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-search mr-1"></i> Optimasi Mesin Pencari (SEO)</h3>
    </div>
    <div class="card-body">
        <small class="text-muted d-block mb-3">Kosongkan field ini jika ingin menggunakan judul dan deskripsi default otomatis dari konten.</small>

        {{-- Live Google Snippet Preview --}}
        <div class="p-3 mb-4 rounded border bg-light">
            <small class="text-muted font-weight-bold text-uppercase d-block mb-2"><i class="fab fa-google mr-1"></i> Simulasi Pratinjau Google Search</small>
            <div style="font-family: Arial, sans-serif; max-width: 600px; background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #e0e0e0;">
                <div style="font-size: 12px; color: #202124; display: flex; align-items: center; gap: 6px;" class="text-truncate">
                    <span style="display: inline-block; width: 16px; height: 16px; background: #6f3a43; border-radius: 50%; color: #fff; font-size: 10px; text-align: center; line-height: 16px;">D</span>
                    <span style="color: #4d5156;">{{ request()->root() }}</span>
                </div>
                <div id="seo-preview-title" style="font-size: 18px; color: #1a0dab; text-decoration: none; font-weight: 400; margin-top: 4px; line-height: 1.3;" class="text-truncate">
                    {{ $seo?->title ?: 'Judul Halaman | ' . App\Models\Setting::get('site_name', 'Dian Mustika') }}
                </div>
                <div id="seo-preview-desc" style="font-size: 13px; color: #4d5156; margin-top: 4px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $seo?->description ?: (App\Models\Setting::get('default_meta_description') ?: 'Deskripsi halaman akan tampil di sini saat dicari pengguna di mesin pencari Google.') }}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="d-flex justify-content-between">
                <label for="seo_title">SEO Title</label>
                <small id="seo_title_count" class="text-muted">0 / 60 karakter</small>
            </div>
            <input
                type="text"
                name="seo_title"
                id="seo_title"
                class="form-control @error('seo_title') is-invalid @enderror"
                value="{{ old('seo_title', $seo?->title) }}"
                placeholder="Judul spesifik untuk Google"
            />
            <small class="form-text text-muted">Disarankan 50–60 karakter agar tampil penuh di Google.</small>
            @error('seo_title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="d-flex justify-content-between">
                <label for="seo_description">Meta Description</label>
                <small id="seo_desc_count" class="text-muted">0 / 160 karakter</small>
            </div>
            <textarea
                name="seo_description"
                id="seo_description"
                rows="3"
                class="form-control @error('seo_description') is-invalid @enderror"
                placeholder="Ringkasan konten yang menarik klik calon pelanggan di Google"
            >{{ old('seo_description', $seo?->description) }}</textarea>
            <small class="form-text text-muted">Disarankan 140–160 karakter dengan memuat kata kunci utama.</small>
            @error('seo_description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <x-admin.input
            name="seo_keywords"
            label="Keywords"
            :value="$seo?->keywords"
            help="Pisahkan dengan koma. Contoh: homecare pascamelahirkan, pijat laktasi, bintaro."
        />

        <x-admin.input
            name="seo_canonical"
            label="Canonical URL"
            type="url"
            :value="$seo?->canonical"
            help="Kosongkan untuk menggunakan URL otomatis."
        />
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var titleInput = document.getElementById('seo_title');
    var descInput = document.getElementById('seo_description');
    var titleCount = document.getElementById('seo_title_count');
    var descCount = document.getElementById('seo_desc_count');
    var previewTitle = document.getElementById('seo-preview-title');
    var previewDesc = document.getElementById('seo-preview-desc');
    var defaultSiteName = ' | {{ addslashes(App\Models\Setting::get('site_name', 'Dian Mustika')) }}';

    function updateTitle() {
        if (!titleInput || !titleCount || !previewTitle) return;
        var len = titleInput.value.length;
        titleCount.textContent = len + ' / 60 karakter';
        if (len >= 50 && len <= 60) {
            titleCount.className = 'font-weight-bold text-success';
        } else if (len > 60) {
            titleCount.className = 'font-weight-bold text-danger';
        } else {
            titleCount.className = 'text-muted';
        }
        if (titleInput.value.trim() !== '') {
            previewTitle.textContent = titleInput.value;
        } else {
            var nameField = document.getElementById('name') || document.getElementById('title');
            var baseName = nameField ? nameField.value.trim() : '';
            previewTitle.textContent = (baseName ? baseName + defaultSiteName : 'Judul Halaman' + defaultSiteName);
        }
    }

    function updateDesc() {
        if (!descInput || !descCount || !previewDesc) return;
        var len = descInput.value.length;
        descCount.textContent = len + ' / 160 karakter';
        if (len >= 140 && len <= 160) {
            descCount.className = 'font-weight-bold text-success';
        } else if (len > 160) {
            descCount.className = 'font-weight-bold text-danger';
        } else {
            descCount.className = 'text-muted';
        }
        if (descInput.value.trim() !== '') {
            previewDesc.textContent = descInput.value;
        } else {
            var shortDescField = document.getElementById('short_description') || document.getElementById('excerpt');
            var baseDesc = shortDescField ? shortDescField.value.trim() : '';
            previewDesc.textContent = baseDesc || 'Deskripsi halaman akan tampil di sini saat dicari pengguna di mesin pencari Google.';
        }
    }

    if (titleInput) {
        titleInput.addEventListener('input', updateTitle);
        updateTitle();
    }
    if (descInput) {
        descInput.addEventListener('input', updateDesc);
        updateDesc();
    }

    var contentNameField = document.getElementById('name') || document.getElementById('title');
    if (contentNameField) {
        contentNameField.addEventListener('input', function () {
            if (titleInput && titleInput.value.trim() === '') updateTitle();
        });
    }

    var contentDescField = document.getElementById('short_description') || document.getElementById('excerpt');
    if (contentDescField) {
        contentDescField.addEventListener('input', function () {
            if (descInput && descInput.value.trim() === '') updateDesc();
        });
    }
});
</script>
