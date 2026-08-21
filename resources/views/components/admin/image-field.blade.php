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

    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif

    @error($name)
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
        });
    });
});
</script>
@endpush
