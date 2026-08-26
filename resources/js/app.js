

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Track clicks for analytics (WA, share, CTA, all monitorable links)
(function () {
    function trackClick(element, label) {
        try {
            var payload = JSON.stringify({
                element: element,
                label: (label || '').slice(0, 255),
                path: location.pathname,
                url: location.href,
            });
            if (navigator.sendBeacon) {
                var blob = new Blob([payload], { type: 'application/json' });
                navigator.sendBeacon('/api/track/click', blob);
            } else {
                fetch('/api/track/click', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: payload,
                    keepalive: true,
                }).catch(function () {});
            }
        } catch (e) {}
    }

    document.addEventListener('click', function (e) {
        var el = e.target.closest('[data-track-click]');
        if (el) {
            trackClick(el.getAttribute('data-track-click'), el.getAttribute('data-track-label') || el.textContent.trim());
            return;
        }
        // Auto-track all monitorable links (a with href, button with data-track auto)
        var link = e.target.closest('a[href]');
        if (link) {
            var href = link.getAttribute('href') || '';
            if (href.startsWith('#') || href.startsWith('javascript:')) return;
            // Only track meaningful links (not admin, not asset)
            if (href.includes('/admin') || href.includes('/storage/')) return;
            var label = (link.textContent || link.getAttribute('aria-label') || href).trim().slice(0, 100);
            var element = 'link:' + (href.startsWith('http') ? new URL(href, location.origin).hostname : 'internal');
            // Throttle: don't double track if already has data-track-click
            // For auto links, use small delay to not double count explicit ones
            trackClick(element, label);
        }
    });
})();
