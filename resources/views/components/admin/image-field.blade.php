@props(['name' => '', 'label' => '', 'value' => '', 'required' => false, 'help' => null])

<div class="form-group">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    @if ($value)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $value) }}" alt="" class="img-thumbnail" style="max-width: 150px;">
        </div>
    @endif

    <div class="custom-file">
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="image/*"
            @if ($required) required @endif
            {{ $attributes->merge(['class' => 'custom-file-input']) }}
        >
        <label class="custom-file-label" for="{{ $name }}">Pilih gambar...</label>
    </div>

    <input type="hidden" name="{{ $name }}_library" id="{{ $name }}_library" value="">
    <div class="mt-2 d-flex align-items-center" style="gap: 8px;">
        <button type="button" class="btn btn-sm btn-outline-primary btn-pick-from-library" data-field="{{ $name }}"><i class="fas fa-images mr-1"></i> Pilih dari Library</button>
        <small class="text-muted">atau upload baru di atas. Dipilih: <span id="{{ $name }}_library_label" class="font-weight-bold">—</span></small>
    </div>
    <img id="{{ $name }}_preview" src="" alt="" class="img-thumbnail mt-2" style="max-width: 150px; display: none;">

    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif

    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
    @error($name.'_library')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.custom-file-input').forEach(function (input) {
        input.addEventListener('change', function (e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih gambar...';
            e.target.nextElementSibling.textContent = fileName;
            // Clear library pick if uploading new
            var lib = document.getElementById(e.target.id + '_library');
            var label = document.getElementById(e.target.id + '_library_label');
            var preview = document.getElementById(e.target.id + '_preview');
            if(lib) lib.value = '';
            if(label) label.textContent = '—';
            if(preview) preview.style.display = 'none';
        });
    });
    // Library pick handler is in media-picker component (delegated)
    document.addEventListener('click', function(e){
        var btn = e.target.closest('.btn-pick-from-library');
        if(btn){
            // preview handling done in media-picker modal callback
            var field = btn.getAttribute('data-field');
            var label = document.getElementById(field + '_library_label');
            // Will be updated by modal callback
        }
    });
    // When media picked, update label/preview (also handled in picker)
    window.addEventListener('mediaPicked', function(e){
        var d = e.detail;
        var field = d.field;
        var label = document.getElementById(field + '_library_label');
        var preview = document.getElementById(field + '_preview');
        var input = document.getElementById(field + '_library');
        if(label) label.textContent = d.path;
        if(preview){ preview.src = d.url; preview.style.display = 'block'; }
        if(input){ input.value = d.path; }
        // Clear file input
        var fileInput = document.getElementById(field);
        if(fileInput) fileInput.value = '';
    });
});
</script>
@endpush
