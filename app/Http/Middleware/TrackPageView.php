<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET public pages, not admin/api/assets
        if (! $request->isMethod('GET')) {
            return $response;
        }

        if (! $response->isSuccessful()) {
            return $response;
        }

        $path = $request->path();
        // Exclude admin, profile, api/track, storage, sitemap, robots, _ignition
        if (
            str_starts_with($path, 'admin') ||
            str_starts_with($path, 'profile') ||
            str_starts_with($path, 'api/track') ||
            str_starts_with($path, 'storage') ||
            $path === 'sitemap.xml' ||
            $path === 'robots.txt' ||
            str_starts_with($path, '_debugbar') ||
            str_starts_with($path, 'livewire')
        ) {
            return $response;
        }

        // Exclude non-HTML (assets with extension, but allow slug with dot handled by 404 already)
        if (preg_match('/\.(css|js|png|jpg|jpeg|webp|svg|ico|woff|woff2|ttf|map)$/i', $request->getRequestUri())) {
            return $response;
        }

        try {
            $ua = $request->userAgent() ?? '';
            // Bot check: jenssegers/agent via CrawlerDetect, plus simple fallback
            $isBot = false;
            if (class_exists(\Jaybizzle\CrawlerDetect\CrawlerDetect::class)) {
                $crawler = new \Jaybizzle\CrawlerDetect\CrawlerDetect(null, $ua);
                $isBot = $crawler->isCrawler();
            }
            if (! $isBot && preg_match('/bot|crawler|spider|crawling|slurp|mediapartners|baidu|yandex|sogou|exabot|facebot|ia_archiver/i', $ua)) {
                $isBot = true;
            }
            if ($isBot) {
                return $response;
            }

            $ip = $request->ip() ?? '0.0.0.0';
            $ipHash = hash('sha256', $ip . config('app.key'));

            // Agent parse
            $agent = new Agent();
            $agent->setUserAgent($ua);
            $device = 'desktop';
            if ($agent->isTablet()) {
                $device = 'tablet';
            } elseif ($agent->isMobile()) {
                $device = 'mobile';
            }
            $browser = $agent->browser() ?: null;
            $os = $agent->platform() ?: null;

            // GeoIP via ip-api.com with cache (only for non-local IPs)
            $country = null;
            $city = null;
            if (! in_array($ip, ['127.0.0.1', '::1']) && ! str_starts_with($ip, '192.168.') && ! str_starts_with($ip, '10.')) {
                $cacheKey = 'geo:' . $ip;
                $geo = Cache::remember($cacheKey, 86400, function () use ($ip) {
                    try {
                        $res = Http::timeout(1)->get('http://ip-api.com/json/' . $ip . '?fields=country,city,status');
                        if ($res->successful()) {
                            $data = $res->json();
                            if (($data['status'] ?? '') === 'success') {
                                return ['country' => $data['country'] ?? null, 'city' => $data['city'] ?? null];
                            }
                        }
                    } catch (\Throwable $e) {
                        // ignore
                    }
                    return null;
                });
                if ($geo) {
                    $country = $geo['country'] ?? null;
                    $city = $geo['city'] ?? null;
                }
            }

            // Title: try from response view share is heavy, just use path-based fallback + route name
            $title = $request->route()?->getName() ?? $path;

            PageView::create([
                'path' => '/' . ltrim($path, '/'),
                'url' => $request->fullUrl(),
                'title' => $title,
                'ip_hash' => $ipHash,
                'country' => $country,
                'city' => $city,
                'device' => $device,
                'browser' => $browser,
                'os' => $os,
                'user_agent' => mb_substr($ua, 0, 500),
                'referrer' => mb_substr($request->header('referer') ?? '', 0, 500),
                'session_id' => $request->session()->getId() ?: null,
                'is_bot' => false,
                'viewed_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Never break the response
            report($e);
        }

        return $response;
    }
}
