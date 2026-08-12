@props(['active' => false, 'href' => '#'])

<a
    href="{{ $href }}"
    @class([
        'block rounded-lg px-3 py-2 font-medium transition',
        'bg-brand-100 text-brand-800' => $active,
        'text-ink/70 hover:bg-ink/5 hover:text-ink' => ! $active,
    ])
    {{ $attributes }}
>
    {{ $slot }}
</a>
