<?php

use App\Http\Controllers\Admin\ArticleCategoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\ArticleController as PublicArticleController;
use App\Http\Controllers\Public\GalleryController as PublicGalleryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LocationController as PublicLocationController;
use App\Http\Controllers\Public\PackageController as PublicPackageController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ServiceController as PublicServiceController;
use App\Http\Controllers\Public\TestimonialController as PublicTestimonialController;
use App\Services\SitemapService;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', function () {
    return response(SitemapService::generate())
        ->header('Content-Type', 'application/xml');
});

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /profile\n\nSitemap: ".url('/sitemap.xml')."\n";

    return response($content)->header('Content-Type', 'text/plain');
});

Route::post('/api/track/click', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'element' => ['required', 'string', 'max:100'],
        'label' => ['nullable', 'string', 'max:255'],
        'path' => ['nullable', 'string', 'max:500'],
        'url' => ['nullable', 'string', 'max:500'],
    ]);

    $ua = $request->userAgent() ?? '';
    $agent = new \Jenssegers\Agent\Agent();
    $agent->setUserAgent($ua);
    $device = 'desktop';
    if ($agent->isTablet()) $device = 'tablet';
    elseif ($agent->isMobile()) $device = 'mobile';

    $ip = $request->ip() ?? '0.0.0.0';
    $country = null; $city = null;
    if (! in_array($ip, ['127.0.0.1', '::1']) && ! str_starts_with($ip, '192.168.') && ! str_starts_with($ip, '10.')) {
        $geo = \Illuminate\Support\Facades\Cache::remember('geo:'.$ip, 86400, function () use ($ip) {
            try {
                $res = \Illuminate\Support\Facades\Http::timeout(1)->get('http://ip-api.com/json/'.$ip.'?fields=country,city,status');
                if ($res->successful()) {
                    $d = $res->json();
                    if (($d['status'] ?? '') === 'success') return ['country' => $d['country'] ?? null, 'city' => $d['city'] ?? null];
                }
            } catch (\Throwable $e) {}
            return null;
        });
        $country = $geo['country'] ?? null;
        $city = $geo['city'] ?? null;
    }

    \App\Models\PageClick::create([
        'path' => $data['path'] ?? parse_url($data['url'] ?? $request->header('referer') ?? '/', PHP_URL_PATH) ?: '/',
        'url' => $data['url'] ?? $request->header('referer') ?? url()->current(),
        'element' => $data['element'],
        'label' => $data['label'] ?? null,
        'ip_hash' => hash('sha256', $ip . config('app.key')),
        'country' => $country,
        'city' => $city,
        'device' => $device,
        'browser' => $agent->browser() ?: null,
        'os' => $agent->platform() ?: null,
        'referrer' => mb_substr($request->header('referer') ?? '', 0, 500),
        'clicked_at' => now(),
    ]);

    return response()->json(['ok' => true]);
})->middleware('throttle:120,1');

Route::get('/', HomeController::class)->name('home');
Route::get('/tentang-kami', [PageController::class, 'about'])->name('about');

Route::get('/layanan', [PublicServiceController::class, 'index'])->name('services.index');
Route::get('/layanan/{service:slug}', [PublicServiceController::class, 'show'])->name('services.show');

Route::get('/paket', [PublicPackageController::class, 'index'])->name('packages.index');

Route::get('/galeri', [PublicGalleryController::class, 'index'])->name('galleries.index');
Route::get('/testimoni', [PublicTestimonialController::class, 'index'])->name('testimonials.index');

Route::get('/artikel', [PublicArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/kategori/{category:slug}', [PublicArticleController::class, 'category'])->name('articles.category');
Route::get('/artikel/{article:slug}', [PublicArticleController::class, 'show'])->name('articles.show');

Route::get('/lokasi', [PublicLocationController::class, 'index'])->name('locations.index');
Route::get('/lokasi/{location:slug}', [PublicLocationController::class, 'show'])->name('locations.show');

Route::get('/faq', [PageController::class, 'faqs'])->name('faqs.index');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact.index');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('service-categories', ServiceCategoryController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('galleries', GalleryController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('article-categories', ArticleCategoryController::class);
    Route::resource('articles', ArticleController::class);
    Route::resource('faqs', FaqController::class);

    Route::resource('users', UserController::class)->except(['show']);

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::delete('activity-logs/{activityLog}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    Route::delete('activity-logs-prune', [ActivityLogController::class, 'prune'])->name('activity-logs.prune');

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
