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
use App\Http\Controllers\Admin\TestimonialController;
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

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
