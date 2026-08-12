@props(['name' => '', 'label' => '', 'value' => '', 'required' => false, 'help' => null])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-ink">{{ $label }}</label>
    @endif

    @if ($value)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $value) }}" alt="" class="h-32 w-32 rounded-lg border border-ink/10 object-cover">
        </div>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="file"
        accept="image/*"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'mt-2 block w-full text-sm text-ink/70 file:mr-3 file:rounded-md file:border-0 file:bg-brand-100 file:px-3 file:py-2 file:font-medium file:text-brand-800 hover:file:bg-brand-200']) }}
    >

    @if ($help)
        <p class="mt-1 text-xs text-ink/60">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
