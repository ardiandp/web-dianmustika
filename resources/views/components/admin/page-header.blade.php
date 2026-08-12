@props([
    'title' => '',
    'description' => null,
    'buttonHref' => null,
    'buttonLabel' => null,
])

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-brand-900">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 text-sm text-ink/60">{{ $description }}</p>
        @endif
    </div>

    @if ($buttonHref && $buttonLabel)
        <a
            href="{{ $buttonHref }}"
            class="inline-flex items-center justify-center rounded-md bg-brand-700 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"
        >
            {{ $buttonLabel }}
        </a>
    @endif
</div>
