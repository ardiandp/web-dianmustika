@props(['title' => '', 'url' => null, 'description' => ''])

@php
    $shareUrl = $url ?? url()->current();
    $shareTitle = $title ?: config('app.name');
    $encodedUrl = rawurlencode($shareUrl);
    $encodedTitle = rawurlencode($shareTitle);
    $encodedDesc = rawurlencode($description ?: $shareTitle);
@endphp

<div class="flex flex-wrap items-center gap-3" data-share-root>
    <span class="text-sm font-semibold text-brand-800">Bagikan:</span>

    {{-- Native Share (visible only if supported) --}}
    <button type="button" data-share-native data-track-click="share_native" data-track-label="{{ $shareTitle }}" hidden class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-brand-700 text-white shadow-sm transition hover:bg-brand-800" title="Bagikan">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
    </button>

    {{-- WhatsApp --}}
    <a href="https://wa.me/?text={{ $encodedTitle }}%20{{ $encodedUrl }}" target="_blank" rel="noopener" data-track-click="share_wa" data-track-label="{{ $shareTitle }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#25D366]/10 text-[#25D366] ring-1 ring-[#25D366]/20 transition hover:bg-[#25D366] hover:text-white" title="Bagikan ke WhatsApp" aria-label="Bagikan ke WhatsApp">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24s-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.24-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"/></svg>
    </a>

    {{-- Facebook --}}
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank" rel="noopener" data-track-click="share_facebook" data-track-label="{{ $shareTitle }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#1877F2]/10 text-[#1877F2] ring-1 ring-[#1877F2]/20 transition hover:bg-[#1877F2] hover:text-white" title="Bagikan ke Facebook" aria-label="Bagikan ke Facebook">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
    </a>

    {{-- X / Twitter --}}
    <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedTitle }}" target="_blank" rel="noopener" data-track-click="share_x" data-track-label="{{ $shareTitle }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-ink/5 text-ink ring-1 ring-ink/10 transition hover:bg-ink hover:text-white" title="Bagikan ke X" aria-label="Bagikan ke X">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
    </a>

    {{-- Copy Link --}}
    <button type="button" data-share-copy data-url="{{ $shareUrl }}" data-track-click="share_copy" data-track-label="{{ $shareTitle }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-brand-50 text-brand-700 ring-1 ring-brand-200 transition hover:bg-brand-700 hover:text-white" title="Salin link" aria-label="Salin link">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.364 5.364l-2.5 4a4.5 4.5 0 01-7.86-4.5l2.5-4M13.19 8.688L10.81 12.027m2.38-3.339l2.5-4a4.5 4.5 0 017.86 4.5l-2.5 4a4.5 4.5 0 01-5.364 1.364M10.81 12.027L8.43 15.366"/></svg>
    </button>

    <span data-share-toast hidden class="rounded-full bg-ink px-3 py-1 text-xs font-medium text-white">Link disalin!</span>
</div>

<script>
(function () {
    var root = document.currentScript ? document.currentScript.previousElementSibling : null;
    // Fallback: find the closest share root before this script
    if (!root || !root.hasAttribute || !root.hasAttribute('data-share-root')) {
        var roots = document.querySelectorAll('[data-share-root]');
        root = roots[roots.length - 1];
    }
    if (!root) return;

    var btnNative = root.querySelector('[data-share-native]');
    var btnCopy = root.querySelector('[data-share-copy]');
    var toast = root.querySelector('[data-share-toast]');

    // Native share
    if (btnNative && navigator.share) {
        btnNative.hidden = false;
        btnNative.addEventListener('click', function () {
            navigator.share({
                title: @json($shareTitle),
                text: @json($shareTitle),
                url: @json($shareUrl)
            }).catch(function () {});
        });
    }

    // Copy link
    if (btnCopy) {
        btnCopy.addEventListener('click', function () {
            var url = btnCopy.getAttribute('data-url') || window.location.href;
            var done = function () {
                if (toast) {
                    toast.hidden = false;
                    setTimeout(function () { toast.hidden = true; }, 2000);
                }
                // Visual feedback on button
                btnCopy.classList.add('bg-brand-700', 'text-white');
                setTimeout(function () { btnCopy.classList.remove('bg-brand-700', 'text-white'); }, 1500);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(done).catch(function () { fallbackCopy(url, done); });
            } else {
                fallbackCopy(url, done);
            }
        });
    }

    function fallbackCopy(text, cb) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'absolute';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(ta);
        if (cb) cb();
    }
})();
</script>
