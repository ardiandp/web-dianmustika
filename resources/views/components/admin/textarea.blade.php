@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'required' => false,
    'rows' => 4,
    'help' => null,
])

<div class="form-group">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'form-control']) }}
    >{{ old($name, $value) }}</textarea>

    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif

    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
