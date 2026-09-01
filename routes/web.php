<?php

use App\Http\Controllers\Admin\ArticleCategoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TreatmentVisitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\ArticleController as PublicArticleController;
use App\Http\Controllers\Public\ConsultationController as PublicConsultationController;
use App\Http\Controllers\Public\GalleryController as PublicGalleryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LocationController as PublicLocationController;
use App\Http\Controllers\Public\PackageController as PublicPackageController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\SearchController;
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
Route::get('/paket/{package:slug}', [PublicPackageController::class, 'show'])->name('packages.show');

Route::get('/galeri', [PublicGalleryController::class, 'index'])->name('galleries.index');
Route::get('/testimoni', [PublicTestimonialController::class, 'index'])->name('testimonials.index');

Route::get('/artikel', [PublicArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/kategori/{category:slug}', [PublicArticleController::class, 'category'])->name('articles.category');
Route::get('/artikel/{article:slug}', [PublicArticleController::class, 'show'])->name('articles.show');

Route::get('/lokasi', [PublicLocationController::class, 'index'])->name('locations.index');
Route::get('/lokasi/{location:slug}', [PublicLocationController::class, 'show'])->name('locations.show');

Route::get('/faq', [PageController::class, 'faqs'])->name('faqs.index');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact.index');

Route::get('/cari', [SearchController::class, 'index'])->name('search.index');
Route::get('/api/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

Route::get('/konsultasi', [PublicConsultationController::class, 'landing'])->name('consultation.landing');
Route::get('/konsultasi/mulai', [PublicConsultationController::class, 'create'])->name('consultation.create');
Route::post('/konsultasi', [PublicConsultationController::class, 'store'])->name('consultation.store');
Route::get('/konsultasi/sukses', [PublicConsultationController::class, 'success'])->name('consultation.success');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->middleware('permission:manage-dashboard')->name('dashboard');

    Route::resource('service-categories', ServiceCategoryController::class)->middleware('permission:manage-service-categories');
    Route::resource('services', ServiceController::class)->middleware('permission:manage-services');
    Route::resource('packages', PackageController::class)->middleware('permission:manage-packages');
    Route::resource('locations', LocationController::class)->middleware('permission:manage-locations');
    Route::resource('galleries', GalleryController::class)->middleware('permission:manage-galleries');
    Route::resource('testimonials', TestimonialController::class)->middleware('permission:manage-testimonials');
    Route::resource('article-categories', ArticleCategoryController::class)->middleware('permission:manage-article-categories');
    Route::resource('articles', ArticleController::class)->middleware('permission:manage-articles');
    Route::resource('faqs', FaqController::class)->middleware('permission:manage-faqs');

    Route::resource('consultations', ConsultationController::class)->only(['index', 'show', 'update', 'destroy'])->middleware('permission:manage-consultations');
    Route::resource('customers', CustomerController::class)->only(['index', 'show', 'destroy'])->middleware('permission:manage-customers');
    Route::get('customers/{customer}/treatment-visits/create', [TreatmentVisitController::class, 'create'])->middleware('permission:manage-treatment-visits')->name('treatment-visits.create');
    Route::post('treatment-visits', [TreatmentVisitController::class, 'store'])->middleware('permission:manage-treatment-visits')->name('treatment-visits.store');
    Route::get('treatment-visits/{treatmentVisit}/edit', [TreatmentVisitController::class, 'edit'])->middleware('permission:manage-treatment-visits')->name('treatment-visits.edit');
    Route::put('treatment-visits/{treatmentVisit}', [TreatmentVisitController::class, 'update'])->middleware('permission:manage-treatment-visits')->name('treatment-visits.update');
    Route::delete('treatment-visits/{treatmentVisit}', [TreatmentVisitController::class, 'destroy'])->middleware('permission:manage-treatment-visits')->name('treatment-visits.destroy');

    Route::resource('users', UserController::class)->except(['show'])->middleware('permission:manage-users');
    Route::resource('roles', RoleController::class)->except(['show'])->middleware('permission:manage-roles');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->middleware('permission:manage-activity-logs')->name('activity-logs.index');
    Route::delete('activity-logs/{activityLog}', [ActivityLogController::class, 'destroy'])->middleware('permission:manage-activity-logs')->name('activity-logs.destroy');
    Route::delete('activity-logs-prune', [ActivityLogController::class, 'prune'])->middleware('permission:manage-activity-logs')->name('activity-logs.prune');

    Route::resource('media', MediaController::class)->only(['index', 'store', 'destroy'])->middleware('permission:manage-media');
    Route::post('media/tinymce', [MediaController::class, 'tinymceUpload'])->middleware('permission:manage-media')->name('media.tinymce');
    Route::get('media/pick', [MediaController::class, 'pick'])->middleware('permission:manage-media')->name('media.pick');

    Route::get('settings', [SettingController::class, 'edit'])->middleware('permission:manage-settings')->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->middleware('permission:manage-settings')->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
