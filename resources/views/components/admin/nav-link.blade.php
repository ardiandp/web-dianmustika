@props(['active' => false, 'href' => '#'])

<li class="nav-item">
    <a
        href="{{ $href }}"
        class="nav-link {{ $active ? 'active' : '' }}"
        {{ $attributes }}
    >
        {{ $slot }}
    </a>
</li>
