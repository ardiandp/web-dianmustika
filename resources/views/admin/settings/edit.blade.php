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
                    <small class="form-text text-muted">JPG, PNG, atau WebP. Disarankan rasio 4:5 atau landscape. Maks 2MB.</small>
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
