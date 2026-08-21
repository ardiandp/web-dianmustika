@props(['name' => '', 'label' => '', 'checked' => false, 'help' => null])

<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input
            type="checkbox"
            name="{{ $name }}"
            value="1"
            id="{{ $name }}"
            @checked($checked)
            {{ $attributes->merge(['class' => 'custom-control-input']) }}
        >
        <label class="custom-control-label" for="{{ $name }}">{{ $label }}</label>
    </div>
    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
</div>
