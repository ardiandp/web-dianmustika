@props(['faq', 'defaultOpen' => false])

<div x-data="{ open: {{ $defaultOpen ? 'true' : 'false' }} }" class="overflow-hidden rounded-2xl bg-white ring-1 ring-ink/5">
    <button type="button" @click="open = !open" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left" aria-expanded="open">
        <span class="text-sm font-semibold text-brand-800 sm:text-base">{{ $faq->question }}</span>
        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700 transition" :class="open ? 'rotate-45' : ''">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
        </span>
    </button>
    <div x-show="open" x-collapse x-cloak>
        <p class="px-5 pb-5 text-sm leading-relaxed text-ink/70">{{ $faq->answer }}</p>
    </div>
</div>
