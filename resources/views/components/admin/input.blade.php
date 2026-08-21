@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'help' => null,
])

<div class="form-group">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'form-control']) }}
    >

    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif

    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
