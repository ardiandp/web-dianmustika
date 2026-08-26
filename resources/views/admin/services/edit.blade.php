<x-layouts.admin title="Edit Layanan">
    <x-admin.page-header title="Edit Layanan" />

    <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- INFORMASI DASAR --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Informasi Dasar</h3>
            </div>
            <div class="card-body">
                <x-admin.select name="service_category_id" label="Kategori" help="Opsional.">
                    <option value="">— Pilih Kategori —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('service_category_id', $service->service_category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </x-admin.select>
                <x-admin.input name="name" label="Nama Layanan" required :value="$service->name" />
                <x-admin.input name="slug" label="Slug" :value="$service->slug" help="Kosongkan untuk dibuat otomatis dari nama. Hanya a-z, 0-9, -." />
                <x-admin.textarea name="short_description" label="Deskripsi Singkat" rows="3" required :value="$service->short_description" help="Ringkasan untuk SEO & card." />
                <x-admin.editor name="description" label="Deskripsi Lengkap" required :value="$service->description" />
            </div>
        </div>

        {{-- DETAIL LAYANAN --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list-ul mr-1"></i> Detail Layanan</h3>
            </div>
            <div class="card-body">
                <x-admin.textarea name="benefits" label="Manfaat" rows="4" :value="implode(PHP_EOL, $service->benefits ?? [])" help="Satu manfaat per baris." />
                <x-admin.textarea name="cocok_untuk" label="Cocok Untuk" rows="3" :value="$service->cocok_untuk" help="Siapa yang cocok menggunakan layanan ini." />
                <x-admin.textarea name="perhatian" label="Perhatian" rows="3" :value="$service->perhatian" help="Batasan atau informasi penting sebelum melakukan layanan." />
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="duration" label="Durasi" :value="$service->duration" help="Contoh: 60 menit, 90 menit." />
                    </div>
                    <div class="col-md-6">
                        <x-admin.select name="tipe_harga" label="Tipe Harga">
                            <option value="tetap" @selected(old('tipe_harga', $service->tipe_harga ?? 'tetap') == 'tetap')>Tetap</option>
                            <option value="mulai_dari" @selected(old('tipe_harga', $service->tipe_harga) == 'mulai_dari')>Mulai dari</option>
                            <option value="per_lokasi" @selected(old('tipe_harga', $service->tipe_harga) == 'per_lokasi')>Per Lokasi</option>
                            <option value="hubungi_kami" @selected(old('tipe_harga', $service->tipe_harga) == 'hubungi_kami')>Hubungi Kami</option>
                        </x-admin.select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="price" label="Harga (angka)" type="number" step="0.01" :value="$service->price" help="Dalam Rupiah. Kosongkan jika Hubungi Kami." />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="harga_label" label="Label Harga" :value="$service->harga_label" help="Contoh: Rp350.000 atau Mulai dari Rp350.000. Kosongkan untuk auto." />
                    </div>
                </div>
                <x-admin.textarea name="note" label="Catatan" rows="3" :value="$service->note" />
            </div>
        </div>

        {{-- MEDIA --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-images mr-1"></i> Media</h3>
            </div>
            <div class="card-body">
                <x-admin.image-field name="image" label="Gambar Utama" :value="$service->image" help="JPG, PNG, atau WebP. Maksimal 2MB." />
                <x-admin.input name="alt_text" label="Teks Alternatif Gambar Utama" :value="$service->alt_text" help="Untuk aksesibilitas & SEO." />
                <hr>
                <label>Galeri Layanan</label>
                <small class="form-text text-muted mb-2">Kelola galeri foto layanan. Centang hapus untuk menghapus gambar.</small>

                @if ($service->galleries->isNotEmpty())
                    <div class="mb-3">
                        <div class="row">
                            @foreach ($service->galleries as $gallery)
                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-2 text-center">
                                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->alt_text }}" class="img-thumbnail mb-2" style="max-height: 100px;">
                                        <p class="small mb-1">{{ $gallery->caption ?: '—' }}</p>
                                        <p class="small text-muted mb-1">Alt: {{ $gallery->alt_text ?: '—' }}</p>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="remove-gallery-{{ $gallery->id }}" name="remove_gallery_ids[]" value="{{ $gallery->id }}">
                                            <label class="custom-control-label text-danger small" for="remove-gallery-{{ $gallery->id }}"><i class="fas fa-trash mr-1"></i> Hapus</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-muted small">Belum ada galeri.</p>
                @endif

                <label class="mt-2">Tambah Gambar Baru</label>
                <div id="gallery-container">
                    <div class="gallery-row border rounded p-3 mb-2">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group mb-1">
                                    <label class="small">Gambar</label>
                                    <input type="file" name="gallery_images[]" accept="image/*" class="form-control-file">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-1">
                                    <label class="small">Alt Text</label>
                                    <input type="text" name="gallery_alt_texts[]" class="form-control form-control-sm" placeholder="Alt text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-1">
                                    <label class="small">Caption</label>
                                    <input type="text" name="gallery_captions[]" class="form-control form-control-sm" placeholder="Caption">
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-gallery mb-1"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="btn-add-gallery" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Tambah Gambar Galeri</button>
                <hr>
                <x-admin.input name="video_url" label="Video URL" type="url" :value="$service->video_url" help="Link YouTube atau video lain. Opsional." />
            </div>
        </div>

        {{-- RESERVASI --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fab fa-whatsapp mr-1"></i> Reservasi</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="cta_text" label="Teks Tombol CTA" :value="$service->cta_text ?? 'Reservasi Sekarang'" help="Default: Reservasi Sekarang" />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="cta_url" label="URL CTA" type="url" :value="$service->cta_url" help="Kosongkan untuk WhatsApp otomatis." />
                    </div>
                </div>
                <small class="text-muted">Jika URL kosong, tombol akan membuka WhatsApp dengan pesan: "Halo Dian Mustika, saya ingin reservasi layanan {nama layanan}."</small>
            </div>
        </div>

        {{-- LOKASI --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-map-marker-alt mr-1"></i> Lokasi Tersedia</h3>
            </div>
            <div class="card-body">
                <small class="text-muted d-block mb-2">Pilih lokasi di mana layanan ini tersedia.</small>
                @php $selectedLocs = old('location_ids', $service->locations->pluck('id')->toArray()); @endphp
                @forelse ($locations as $loc)
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="loc-{{ $loc->id }}" name="location_ids[]" value="{{ $loc->id }}" @checked(in_array($loc->id, $selectedLocs))>
                        <label class="custom-control-label" for="loc-{{ $loc->id }}">{{ $loc->name }}</label>
                    </div>
                @empty
                    <p class="text-muted">Belum ada lokasi.</p>
                @endforelse
            </div>
        </div>

        {{-- LAYANAN TERKAIT --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-link mr-1"></i> Layanan Terkait</h3>
            </div>
            <div class="card-body">
                <small class="text-muted d-block mb-2">Pilih layanan lain yang berkaitan. Untuk internal linking.</small>
                @php $selectedRelated = old('related_service_ids', $service->relatedServices->pluck('id')->toArray()); @endphp
                @forelse ($allServices as $svc)
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="rel-{{ $svc->id }}" name="related_service_ids[]" value="{{ $svc->id }}" @checked(in_array($svc->id, $selectedRelated))>
                        <label class="custom-control-label" for="rel-{{ $svc->id }}">{{ $svc->name }}</label>
                    </div>
                @empty
                    <p class="text-muted">Belum ada layanan lain.</p>
                @endforelse
            </div>
        </div>

        {{-- ARTIKEL TERKAIT --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-newspaper mr-1"></i> Artikel Terkait</h3>
            </div>
            <div class="card-body">
                <small class="text-muted d-block mb-2">Pilih artikel yang relevan dengan layanan ini.</small>
                @php $selectedArticles = old('article_ids', $service->articles->pluck('id')->toArray()); @endphp
                @forelse ($articles as $art)
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="art-{{ $art->id }}" name="article_ids[]" value="{{ $art->id }}" @checked(in_array($art->id, $selectedArticles))>
                        <label class="custom-control-label" for="art-{{ $art->id }}">{{ $art->title }}</label>
                    </div>
                @empty
                    <p class="text-muted">Belum ada artikel.</p>
                @endforelse
            </div>
        </div>

        {{-- FAQ --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-question-circle mr-1"></i> FAQ Layanan</h3>
            </div>
            <div class="card-body">
                <small class="text-muted d-block mb-3">Kelola FAQ untuk layanan ini. FAQ akan tampil di halaman detail & menghasilkan FAQ schema.</small>
                <div id="faq-container">
                    @foreach ($service->faqs as $idx => $faq)
                        <div class="faq-row border rounded p-3 mb-2" data-existing="1">
                            <input type="hidden" name="faqs[{{ $idx }}][id]" value="{{ $faq->id }}">
                            <div class="form-group">
                                <label class="small">Pertanyaan</label>
                                <input type="text" name="faqs[{{ $idx }}][question]" value="{{ old('faqs.'.$idx.'.question', $faq->question) }}" class="form-control form-control-sm" placeholder="Pertanyaan">
                            </div>
                            <div class="form-group">
                                <label class="small">Jawaban</label>
                                <textarea name="faqs[{{ $idx }}][answer]" rows="2" class="form-control form-control-sm" placeholder="Jawaban">{{ old('faqs.'.$idx.'.answer', $faq->answer) }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label class="small">Urutan</label>
                                    <input type="number" name="faqs[{{ $idx }}][sort_order]" value="{{ old('faqs.'.$idx.'.sort_order', $faq->sort_order) }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-3 d-flex align-items-end">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="faq-active-{{ $idx }}" name="faqs[{{ $idx }}][is_active]" value="1" @checked(old('faqs.'.$idx.'.is_active', $faq->is_active))>
                                        <label class="custom-control-label small" for="faq-active-{{ $idx }}">Aktif</label>
                                    </div>
                                </div>
                                <div class="col-6 d-flex align-items-end justify-content-end">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-faq mb-2" data-faq-id="{{ $faq->id }}"><i class="fas fa-trash mr-1"></i> Hapus</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="removed-faqs"></div>
                <button type="button" id="btn-add-faq" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Tambah FAQ</button>
            </div>
        </div>

        {{-- SEO --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-search mr-1"></i> SEO</h3>
            </div>
            <div class="card-body">
                <small class="text-muted d-block mb-3">Kosongkan untuk nilai default otomatis.</small>
                <x-admin.input name="focus_keyword" label="Focus Keyword" :value="$service->focus_keyword" help="Keyword utama. Contoh: perawatan pasca melahirkan" />
                <x-admin.textarea name="secondary_keywords" label="Secondary Keywords" rows="3" :value="$service->secondary_keywords" help="Keyword pendukung, pisahkan koma atau baris baru." />
                <x-admin.seo-fields :seo="$service?->seo ?? null" />
            </div>
        </div>

        {{-- STATUS --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-toggle-on mr-1"></i> Status</h3>
            </div>
            <div class="card-body">
                <x-admin.checkbox name="is_featured" label="Unggulan" :checked="$service->is_featured" />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="$service->is_active" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.services.index')" />
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gallery repeater
        var galleryContainer = document.getElementById('gallery-container');
        document.getElementById('btn-add-gallery').addEventListener('click', function () {
            var row = document.createElement('div');
            row.className = 'gallery-row border rounded p-3 mb-2';
            row.innerHTML = '<div class="row">'
                + '<div class="col-md-5"><div class="form-group mb-1"><label class="small">Gambar</label><input type="file" name="gallery_images[]" accept="image/*" class="form-control-file"></div></div>'
                + '<div class="col-md-3"><div class="form-group mb-1"><label class="small">Alt Text</label><input type="text" name="gallery_alt_texts[]" class="form-control form-control-sm" placeholder="Alt text"></div></div>'
                + '<div class="col-md-3"><div class="form-group mb-1"><label class="small">Caption</label><input type="text" name="gallery_captions[]" class="form-control form-control-sm" placeholder="Caption"></div></div>'
                + '<div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm btn-remove-gallery mb-1"><i class="fas fa-trash"></i></button></div>'
                + '</div>';
            galleryContainer.appendChild(row);
        });
        galleryContainer.addEventListener('click', function (e) {
            if (e.target.closest('.btn-remove-gallery')) {
                e.target.closest('.gallery-row').remove();
            }
        });

        // FAQ repeater
        var faqContainer = document.getElementById('faq-container');
        var removedFaqs = document.getElementById('removed-faqs');
        var faqIndex = faqContainer.querySelectorAll('.faq-row').length;
        document.getElementById('btn-add-faq').addEventListener('click', function () {
            var row = document.createElement('div');
            row.className = 'faq-row border rounded p-3 mb-2';
            row.innerHTML = '<div class="form-group"><label class="small">Pertanyaan</label><input type="text" name="faqs[' + faqIndex + '][question]" class="form-control form-control-sm" placeholder="Pertanyaan"></div>'
                + '<div class="form-group"><label class="small">Jawaban</label><textarea name="faqs[' + faqIndex + '][answer]" rows="2" class="form-control form-control-sm" placeholder="Jawaban"></textarea></div>'
                + '<div class="row"><div class="col-3"><label class="small">Urutan</label><input type="number" name="faqs[' + faqIndex + '][sort_order]" value="' + faqIndex + '" class="form-control form-control-sm"></div>'
                + '<div class="col-3 d-flex align-items-end"><div class="custom-control custom-checkbox mb-2"><input type="checkbox" class="custom-control-input" id="faq-active-' + faqIndex + '" name="faqs[' + faqIndex + '][is_active]" value="1" checked><label class="custom-control-label small" for="faq-active-' + faqIndex + '">Aktif</label></div></div>'
                + '<div class="col-6 d-flex align-items-end justify-content-end"><button type="button" class="btn btn-danger btn-sm btn-remove-faq mb-2"><i class="fas fa-trash mr-1"></i> Hapus</button></div></div>';
            faqContainer.appendChild(row);
            faqIndex++;
        });
        faqContainer.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-remove-faq');
            if (!btn) return;
            var row = btn.closest('.faq-row');
            var faqId = btn.getAttribute('data-faq-id');
            if (faqId) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_faq_ids[]';
                input.value = faqId;
                removedFaqs.appendChild(input);
            }
            row.remove();
        });
    });
    </script>
    @endpush
</x-layouts.admin>
