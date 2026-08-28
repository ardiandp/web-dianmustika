<div class="modal fade" id="mediaPickerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-images mr-1"></i> Pilih Media</h5>
                <input type="search" id="mediaPickerSearch" class="form-control form-control-sm ml-2" style="max-width: 250px;" placeholder="Cari nama...">
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row" id="mediaPickerGrid">
                    <div class="col-12 text-center text-muted py-4">Memuat media...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var pendingField = null;
    var pendingTinyMCE = null;
    var searchTimer = null;

    function loadMedia(query) {
        var grid = document.getElementById('mediaPickerGrid');
        var url = '{{ route('admin.media.pick') }}' + (query ? '?q=' + encodeURIComponent(query) : '');
        grid.innerHTML = '<div class="col-12 text-center text-muted py-4">Memuat media...</div>';
        fetch(url)
            .then(function (r) { return r.json(); })
            .then(function (items) {
                if (!items.length) {
                    grid.innerHTML = '<div class="col-12 text-center text-muted py-4">Tidak ada media. Upload dulu di Media Library.</div>';
                    return;
                }
                var html = '';
                items.forEach(function (m) {
                    html += '<div class="col-lg-3 col-md-4 col-6 mb-3">' +
                        '<div class="card h-100 shadow-sm media-picker-item" data-path="' + m.file_path + '" data-url="' + m.url + '" data-alt="' + (m.alt_text || '') + '" role="button" style="cursor:pointer;">' +
                        '<div class="d-flex align-items-center justify-content-center bg-light" style="height:120px; overflow:hidden;">' +
                        '<img src="' + m.thumb + '" alt="' + (m.alt_text || m.name) + '" style="max-height:120px; max-width:100%; object-fit:contain;">' +
                        '</div>' +
                        '<div class="card-body p-2"><p class="small font-weight-bold text-truncate mb-0" style="margin:0;" title="' + m.name + '">' + m.name + '</p></div>' +
                        '</div></div>';
                });
                grid.innerHTML = '<div class="col-12">' + '<div class="row">' + html + '</div></div>';
                grid.querySelectorAll('.media-picker-item').forEach(function (el) {
                    el.addEventListener('click', function () {
                        var path = el.getAttribute('data-path');
                        var url = el.getAttribute('data-url');
                        var alt = el.getAttribute('data-alt');
                        selectMedia(path, url, alt);
                    });
                });
            })
            .catch(function () {
                grid.innerHTML = '<div class="col-12 text-center text-danger py-4">Gagal memuat media.</div>';
            });
    }

    function selectMedia(path, url, alt) {
        if (pendingTinyMCE) {
            var cb = pendingTinyMCE;
            pendingTinyMCE = null;
            pendingField = null;
            cb(url, { alt: alt });
        } else if (pendingField) {
            window.dispatchEvent(new CustomEvent('mediaPicked', {
                detail: { field: pendingField, path: path, url: url, alt: alt }
            }));
        }
        pendingField = null;
        let modal = document.getElementById('mediaPickerModal');
        if (window.jQuery && modal) {
            jQuery(modal).modal('hide');
        }
    }

    function openPicker(field) {
        pendingField = field;
        pendingTinyMCE = null;
        var search = document.getElementById('mediaPickerSearch');
        if (search) search.value = '';
        loadMedia('');
        if (window.jQuery) {
            jQuery('#mediaPickerModal').modal('show');
        }
    }

    window.openMediaPicker = openPicker;

    window.mediaPickerCallbackForTinyMCE = function (callback, value, meta) {
        pendingTinyMCE = callback;
        pendingField = null;
        var search = document.getElementById('mediaPickerSearch');
        if (search) search.value = '';
        loadMedia('');
        if (window.jQuery) {
            jQuery('#mediaPickerModal').modal('show');
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        var search = document.getElementById('mediaPickerSearch');
        if (search) {
            search.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    loadMedia(search.value);
                }, 300);
            });
        }

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-pick-from-library');
            if (btn) {
                e.preventDefault();
                var field = btn.getAttribute('data-field');
                openPicker(field);
            }
        });
    });
})();
</script>
@endpush
