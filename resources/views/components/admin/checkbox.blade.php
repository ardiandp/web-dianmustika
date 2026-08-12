@props(['name' => '', 'label' => '', 'checked' => false, 'help' => null])

<label class="flex items-start gap-2">
    <input
        type="checkbox"
        name="{{ $name }}"
        value="1"
        @checked($checked)
        {{ $attributes->merge(['class' => 'mt-0.5 rounded border-ink/30 text-brand-600 focus:ring-brand-500']) }}
    >
    <span>
        <span class="block text-sm font-medium text-ink">{{ $label }}</span>
        @if ($help)
            <span class="block text-xs text-ink/60">{{ $help }}</span>
        @endif
    </span>
</label>
