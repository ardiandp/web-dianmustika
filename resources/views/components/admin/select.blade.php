@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'required' => false,
    'help' => null,
])

<div class="form-group">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'form-control']) }}
    >
        {{ $slot }}
    </select>

    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif

    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
