<x-layouts.admin title="Pengaturan Website">
    <x-admin.page-header title="Pengaturan Website" description="Informasi umum dan kontak yang tampil di seluruh website." />

    @php
        $hours = json_decode((string) $settings->get('opening_hours'), true) ?: [];
        $hoursText = '';
        foreach ($hours as $day => $time) {
            $hoursText .= $day . ' = ' . $time . "\n";
        }
    @endphp

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-globe mr-1"></i> Informasi Umum</h3>
            </div>
            <div class="card-body">
                <x-admin.input name="site_name" label="Nama Website" required :value="$settings->get('site_name')" />
                <x-admin.input name="site_tagline" label="Tagline" :value="$settings->get('site_tagline')" />
                <x-admin.editor name="site_description" label="Deskripsi Website" :value="$settings->get('site_description')" />
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-image mr-1"></i> Logo & Ikon</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Logo Website</label>
                            @if ($settings->get('logo'))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings->get('logo')) }}" alt="Logo" class="img-thumbnail" style="max-height: 80px;">
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="logo" id="logo" accept="image/*" class="custom-file-input">
                                <label class="custom-file-label" for="logo">Pilih logo...</label>
                            </div>
                            <small class="form-text text-muted">Format: JPG, PNG, atau SVG. Disarankan transparan. Maks 2MB.</small>
                            @error('logo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Favicon (Ikon Tab Browser)</label>
                            @if ($settings->get('favicon'))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings->get('favicon')) }}" alt="Favicon" class="img-thumbnail" style="max-height: 64px;">
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="favicon" id="favicon" accept="image/*" class="custom-file-input">
                                <label class="custom-file-label" for="favicon">Pilih favicon...</label>
                            </div>
                            <small class="form-text text-muted">Format: ICO, PNG, atau SVG. Ukuran 32x32px atau 192x192px. Maks 1MB.</small>
                            @error('favicon')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-search mr-1"></i> Pengaturan SEO & Meta Tag Default</h3>
            </div>
            <div class="card-body">
                <small class="text-muted d-block mb-3">Pengaturan meta tag global yang akan digunakan sebagai default di seluruh halaman website.</small>

                <div class="form-group">
                    <label>Default Meta Title</label>
                    <input type="text" name="default_meta_title" id="default_meta_title" class="form-control" value="{{ old('default_meta_title', $settings->get('default_meta_title', 'Dian Mustika | Homecare Pascamelahirkan, Pijat Laktasi & Perawatan Ibu Bayi')) }}" placeholder="Judul default website di Google">
                    <small class="form-text text-muted">Disarankan 50–60 karakter untuk hasil optimal di Google SERP.</small>
                </div>

                <div class="form-group">
                    <label>Default Meta Description</label>
                    <textarea name="default_meta_description" id="default_meta_description" class="form-control" rows="3" placeholder="Deskripsi ringkas yang muncul di hasil pencarian Google">{{ old('default_meta_description', $settings->get('default_meta_description', 'Layanan homecare pascamelahirkan profesional Dian Mustika: perawatan nifas tradisional & modern, pijat laktasi/breastcare, baby massage, dan spa ibu hamil di rumah Anda. Konsultasi gratis via WhatsApp.')) }}</textarea>
                    <small class="form-text text-muted">Disarankan 150–160 karakter agar tidak terpotong di hasil pencarian.</small>
                </div>

                <div class="form-group">
                    <label>Default Keywords</label>
                    <input type="text" name="default_meta_keywords" class="form-control" value="{{ old('default_meta_keywords', $settings->get('default_meta_keywords', 'homecare pascamelahirkan, pijat nifas, pijat laktasi, breastcare, baby massage, bengkung tradisional, bidan homecare, dian mustika')) }}" placeholder="Kata kunci dipisahkan dengan koma">
                    <small class="form-text text-muted">Pisahkan dengan koma.</small>
                </div>

                <div class="form-group">
                    <label>Default Share Image (OpenGraph / WhatsApp Preview)</label>
                    @if ($settings->get('default_og_image'))
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $settings->get('default_og_image')) }}" alt="Default OG Image" class="img-thumbnail" style="max-height: 140px;">
                        </div>
                    @endif
                    <div class="custom-file">
                        <input type="file" name="default_og_image" id="default_og_image" accept="image/*" class="custom-file-input">
                        <label class="custom-file-label" for="default_og_image">Pilih gambar preview share...</label>
                    </div>
                    <input type="hidden" name="default_og_image_library" id="default_og_image_library" value="{{ $settings->get('default_og_image', '') }}">
                    <div class="mt-2 d-flex align-items-center" style="gap: 8px;">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-pick-from-library" data-field="default_og_image"><i class="fas fa-images mr-1"></i> Pilih dari Library</button>
                        <small class="text-muted">Dipilih: <span id="default_og_image_library_label" class="font-weight-bold">—</span></small>
                    </div>
                    <img id="default_og_image_preview" src="" alt="" class="img-thumbnail mt-2" style="max-width: 150px; display: none;">
                    <small class="form-text text-muted">Ukuran ideal: 1200×630 px (format JPG/PNG/WebP, maks 2MB). Gambar ini akan otomatis muncul sebagai thumbnail saat link website dibagikan ke WhatsApp, Telegram, Facebook, atau Twitter.</small>
                    @error('default_og_image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-home mr-1"></i> Hero / Banner Utama</h3>
            </div>
            <div class="card-body">
                <small class="text-muted d-block mb-3">Bagian utama di halaman depan. Kosongkan untuk menggunakan nilai default.</small>

                <div class="form-group">
                    <label>Badge Teks</label>
                    <input type="text" name="hero_badge" class="form-control" value="{{ old('hero_badge', $settings->get('hero_badge', 'Beauty & Wellness')) }}" placeholder="Beauty & Wellness">
                </div>

                <div class="form-group">
                    <label>Heading / Judul</label>
                    <input type="text" name="hero_heading" class="form-control" value="{{ old('hero_heading', $settings->get('hero_heading', 'Perawatan Tubuh & Kecantikan untuk Anda yang Ingin Merawat Diri')) }}" placeholder="Judul utama hero">
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="hero_description" class="form-control" rows="3" placeholder="Deskripsi singkat tentang website">{{ old('hero_description', $settings->get('hero_description', 'Dian Mustika membantu Anda merawat diri dengan layanan profesional, nyaman, dan elegan — dari massage relaksasi, slimming, hingga perawatan pasca melahirkan.')) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Gambar Hero</label>
                    @if ($settings->get('hero_image'))
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $settings->get('hero_image')) }}" alt="Hero" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif
                    <div class="custom-file">
                        <input type="file" name="hero_image" id="hero_image" accept="image/*" class="custom-file-input">
                        <label class="custom-file-label" for="hero_image">Pilih gambar hero...</label>
                    </div>
                    <input type="hidden" name="hero_image_library" id="hero_image_library" value="{{ $settings->get('hero_image', '') }}">
                    <div class="mt-2 d-flex align-items-center" style="gap: 8px;">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-pick-from-library" data-field="hero_image"><i class="fas fa-images mr-1"></i> Pilih dari Library</button>
                        <small class="text-muted">Dipilih: <span id="hero_image_library_label" class="font-weight-bold">—</span></small>
                    </div>
                    <img id="hero_image_preview" src="" alt="" class="img-thumbnail mt-2" style="max-width: 150px; display: none;">
                    <small class="form-text text-muted">JPG, PNG, atau WebP. Rasio ideal 5:4 (800×650) atau Retina 1200×975. Minimal 600×490. Maks 2MB. Gambar akan tampil utuh tanpa terpotong.</small>
                    @error('hero_image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <hr>
                <h5 class="mb-3">Statistik (angka di bawah hero)</h5>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Stat 1 - Nilai</label>
                            <input type="text" name="hero_stat1_value" class="form-control" value="{{ old('hero_stat1_value', $settings->get('hero_stat1_value', '15+')) }}" placeholder="15+">
                        </div>
                        <div class="form-group">
                            <label>Stat 1 - Label</label>
                            <input type="text" name="hero_stat1_label" class="form-control" value="{{ old('hero_stat1_label', $settings->get('hero_stat1_label', 'Jenis Perawatan')) }}" placeholder="Jenis Perawatan">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Stat 2 - Nilai</label>
                            <input type="text" name="hero_stat2_value" class="form-control" value="{{ old('hero_stat2_value', $settings->get('hero_stat2_value', '3')) }}" placeholder="3">
                        </div>
                        <div class="form-group">
                            <label>Stat 2 - Label</label>
                            <input type="text" name="hero_stat2_label" class="form-control" value="{{ old('hero_stat2_label', $settings->get('hero_stat2_label', 'Lokasi Cabang')) }}" placeholder="Lokasi Cabang">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Stat 3 - Nilai</label>
                            <input type="text" name="hero_stat3_value" class="form-control" value="{{ old('hero_stat3_value', $settings->get('hero_stat3_value', '100%')) }}" placeholder="100%">
                        </div>
                        <div class="form-group">
                            <label>Stat 3 - Label</label>
                            <input type="text" name="hero_stat3_label" class="form-control" value="{{ old('hero_stat3_label', $settings->get('hero_stat3_label', 'Terapis Berpengalaman')) }}" placeholder="Terapis Berpengalaman">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-phone mr-1"></i> Kontak</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="whatsapp" label="WhatsApp" :value="$settings->get('whatsapp')" help="Format: 628xxxxxxxxxx" />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="phone" label="Telepon" :value="$settings->get('phone')" />
                    </div>
                </div>
                <x-admin.input name="email" label="Email" type="email" :value="$settings->get('email')" />
                <x-admin.textarea name="address" label="Alamat" rows="2" :value="$settings->get('address')" />
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clock mr-1"></i> Jam Operasional</h3>
            </div>
            <div class="card-body">
                <x-admin.textarea name="opening_hours" label="Jam Operasional" rows="4" :value="$hoursText" help="Satu baris per hari. Format: Senin = 09.00 - 21.00" />
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-share-alt mr-1"></i> Media Sosial</h3>
            </div>
            <div class="card-body">
                <x-admin.input name="social_instagram" label="Instagram" type="url" :value="$settings->get('social_instagram')" />
                <x-admin.input name="social_facebook" label="Facebook" type="url" :value="$settings->get('social_facebook')" />
                <x-admin.input name="social_tiktok" label="TikTok" type="url" :value="$settings->get('social_tiktok')" />
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cog mr-1"></i> Lainnya</h3>
            </div>
            <div class="card-body">
                <x-admin.textarea name="google_maps_embed" label="Embed Google Maps" rows="5" :value="$settings->get('google_maps_embed')" help="Kode embed peta (iframe)." />
                <x-admin.input name="footer_copyright" label="Teks Copyright" :value="$settings->get('footer_copyright')" />
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-robot mr-1"></i> Asisten AI (Chat)</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="chat_ai_enabled" id="chat_ai_enabled" value="1" @checked((bool) $settings->get('chat_ai_enabled', true))>
                        <label class="custom-control-label" for="chat_ai_enabled">Aktifkan chat AI di halaman website</label>
                    </div>
                    <small class="form-text text-muted">Menampilkan widget chat asisten AI di pojok kanan bawah halaman publik. Terhubung ke Ollama di PC lokal.</small>
                </div>
                <x-admin.textarea name="chat_ai_welcome" label="Pesan Sambutan" rows="3" :value="$settings->get('chat_ai_welcome', config('ai-chat.welcome'))" help="Pesan pertama yang muncul saat pengunjung membuka chat." />
                <div class="form-group">
                    <label>Pengetahuan yang boleh diakses AI</label>
                    @php($chatSources = (array) \App\Models\Setting::get('chat_ai_sources', config('ai-chat.sources', [])))
                    @foreach ([
                        'layanan' => 'Layanan — nama, harga & deskripsi treatment',
                        'paket' => 'Paket & Promo — harga normal/promo & periode',
                        'faq' => 'FAQ — tanya-jawab umum',
                        'lokasi' => 'Lokasi — alamat, kontak, jam buka cabang',
                        'artikel' => 'Artikel — konten blog/tips terbit',
                    ] as $sourceKey => $sourceLabel)
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="chat_ai_sources[]" value="{{ $sourceKey }}" id="source_{{ $sourceKey }}" @checked(in_array($sourceKey, $chatSources, true))>
                            <label class="custom-control-label" for="source_{{ $sourceKey }}">{{ $sourceLabel }}</label>
                        </div>
                    @endforeach
                    <small class="form-text text-muted">AI hanya menjawab dari sumber yang dicentang. Profil usaha (nama, kontak, jam operasional) selalu ikut. Perubahan berlaku segera.</small>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.dashboard')" />
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.custom-file-input').forEach(function (input) {
            input.addEventListener('change', function (e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file...';
                e.target.nextElementSibling.textContent = fileName;
            });
        });
    });
    </script>
    @endpush
</x-layouts.admin>
