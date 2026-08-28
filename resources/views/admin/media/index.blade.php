<x-layouts.admin title="Media Library">
    <x-admin.page-header title="Media Library" description="WordPress-like: 1 gambar bisa dipakai berkali-kali di semua halaman. Upload sekali, pilih berkali-kali. Deduplikasi otomatis via hash + thumbnail 300 & 800." />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-cloud-upload-alt mr-1"></i> Upload Media</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="row align-items-end">
                @csrf
                <div class="col-md-5">
                    <div class="form-group mb-0">
                        <label>Pilih File</label>
                        <div class="custom-file">
                            <input type="file" name="file" id="file" class="custom-file-input" required>
                            <label class="custom-file-label" for="file">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted">Gambar (JPG, PNG, WebP, SVG, GIF) & Dokumen (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, RTF, CSV). Maks 15MB.</small>
                        @error('file')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label>Alt Text</label>
                        <input type="text" name="alt_text" class="form-control" placeholder="Alt text (opsional)">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label>Caption</label>
                        <input type="text" name="caption" class="form-control" placeholder="Caption (opsional)">
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-upload mr-1"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-images mr-1"></i> Daftar Media</h3>
            <div class="card-tools">
                <div class="d-flex" style="gap: 6px;">
                    <select name="type" id="mediaType" class="form-control form-control-sm" style="width: 130px;">
                        <option value="all" @selected(request('type','all')=='all')>Semua</option>
                        <option value="image" @selected(request('type')=='image')>Gambar</option>
                        <option value="document" @selected(request('type')=='document')>Dokumen (PDF/DOC)</option>
                        <option value="svg" @selected(request('type')=='svg')>SVG</option>
                    </select>
                    <div class="input-group input-group-sm" style="width: 220px;">
                        <input type="search" name="q" id="mediaSearch" value="{{ request('q') }}" placeholder="Cari nama gambar..." class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text" id="mediaSearchClear" style="cursor:pointer; display:none;"><i class="fas fa-times"></i></span>
                        </div>
                    </div>
                    <button type="button" id="mediaSearchReset" class="btn btn-secondary btn-sm" style="display: none;">Reset</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="mediaGrid">
                @include('admin.media._grid', ['media' => $media])
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        var searchTimer = null;
        var baseUrl = '{{ route('admin.media.index') }}';
        var searchInput = document.getElementById('mediaSearch');
        var typeSelect = document.getElementById('mediaType');
        var clearBtn = document.getElementById('mediaSearchClear');
        var resetBtn = document.getElementById('mediaSearchReset');
        var grid = document.getElementById('mediaGrid');

        // Upload form file input (top) — update label
        var uploadFile = document.getElementById('file');
        if (uploadFile) {
            uploadFile.addEventListener('change', function (e) {
                var name = e.target.files[0] ? e.target.files[0].name : 'Pilih file...';
                e.target.nextElementSibling.textContent = name;
            });
        }

        function bindItemHandlers() {
            grid.querySelectorAll('.custom-file-input').forEach(function (input) {
                input.addEventListener('change', function (e) {
                    var name = e.target.files[0] ? e.target.files[0].name : 'Pilih file...';
                    e.target.nextElementSibling.textContent = name;
                });
            });
            grid.querySelectorAll('.btn-copy-url').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var url = btn.getAttribute('data-url');
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(url).then(function () {
                            Swal.fire({ icon: 'success', title: 'URL disalin!', timer: 1200, showConfirmButton: false });
                        });
                    } else {
                        var ta = document.createElement('textarea');
                        ta.value = url;
                        document.body.appendChild(ta);
                        ta.select();
                        document.execCommand('copy');
                        document.body.removeChild(ta);
                        Swal.fire({ icon: 'success', title: 'URL disalin!', timer: 1200, showConfirmButton: false });
                    }
                });
            });
            grid.querySelectorAll('.btn-pick-media').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    if (window.opener && window.opener.mediaPickerCallback) {
                        window.opener.mediaPickerCallback({ path: btn.dataset.path, url: btn.dataset.url, alt: btn.dataset.alt });
                        window.close();
                    }
                });
            });
        }

        function fetchResults(page) {
            var q = searchInput.value.trim();
            var type = typeSelect.value;
            var params = new URLSearchParams();
            if (q) params.set('q', q);
            if (type && type !== 'all') params.set('type', type);
            if (page && page > 1) params.set('page', page);
            var url = baseUrl + (params.toString() ? '?' + params.toString() : '');

            clearBtn.style.display = q ? '' : 'none';
            resetBtn.style.display = (q || (type && type !== 'all')) ? '' : 'none';

            grid.innerHTML = '<p class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin mr-1"></i>Memuat...</p>';

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    grid.innerHTML = data.html;
                    bindItemHandlers();
                    grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(function () {
                    grid.innerHTML = '<p class="text-center text-danger py-4">Gagal memuat media.</p>';
                });
        }

        // Search as you type (debounced)
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () { fetchResults(1); }, 300);
        });

        // Filter by type
        typeSelect.addEventListener('change', function () { fetchResults(1); });

        // Clear (x) button
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            fetchResults(1);
            searchInput.focus();
        });

        // Reset button
        resetBtn.addEventListener('click', function () {
            searchInput.value = '';
            typeSelect.value = 'all';
            fetchResults(1);
            searchInput.focus();
        });

        // Pagination clicks (AJAX) — delegate on grid
        grid.addEventListener('click', function (e) {
            var link = e.target.closest('a.page-link');
            if (link) {
                e.preventDefault();
                var url = new URL(link.href, window.location.origin);
                var page = url.searchParams.get('page') || 1;
                fetchResults(parseInt(page, 10));
            }
        });

        // Initial binding for copy/pick buttons
        bindItemHandlers();
        // Set clear/reset visibility on load if there are filters
        var initialQ = searchInput.value.trim();
        var initialType = typeSelect.value;
        clearBtn.style.display = initialQ ? '' : 'none';
        resetBtn.style.display = (initialQ || (initialType && initialType !== 'all')) ? '' : 'none';
    })();
    </script>
    @endpush
</x-layouts.admin>
