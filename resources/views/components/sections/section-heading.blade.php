@props(['title', 'description' => '', 'align' => 'center', 'light' => false])

<div @class(['max-w-2xl', $align === 'center' ? 'mx-auto text-center' : 'text-left'])>
    <span @class([
        'inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em]',
        $light ? 'text-gold-400' : 'text-gold-600',
    ])>
        <span class="h-px w-6 bg-gold-500/60"></span>
        {{ $slot }}
        @if ($align === 'center')
            <span class="h-px w-6 bg-gold-500/60"></span>
        @endif
    </span>
    <h2 @class([
        'mt-3 font-display text-3xl font-semibold sm:text-4xl',
        $light ? 'text-cream' : 'text-brand-800',
    ])>
        {{ $title }}
    </h2>
    @if ($description)
        <p @class(['mt-4 text-base leading-relaxed', $light ? 'text-brand-100/70' : 'text-ink/70'])>
            {{ $description }}
        </p>
    @endif
</div>
