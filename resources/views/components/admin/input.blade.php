@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'help' => null,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-ink">{{ $label }}</label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-md border-ink/20 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm']) }}
    >

    @if ($help)
        <p class="mt-1 text-xs text-ink/60">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
