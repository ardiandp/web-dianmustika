@props(['name', 'label' => '', 'value' => ''])

<div class="form-group">
    @if($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif
    <textarea name="{{ $name }}" id="{{ $name }}" {{ $attributes->merge(['class' => 'form-control js-tinymce']) }}>{{ old($name, $value) }}</textarea>
    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
