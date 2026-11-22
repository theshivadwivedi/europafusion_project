<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ExamPrepController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations');
Route::get('/study-in-germany', [DestinationController::class, 'germany'])->name('germany');
Route::get('/study-in-{country}', [PageController::class, 'country'])->where('country', '[a-z-]+')->name('country');
Route::get('/germany/{pathway}', [PageController::class, 'pathway'])->where('pathway', 'studienkolleg|bachelor|master|medicine')->name('pathway');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/exam-prep', [ExamPrepController::class, 'index'])->name('exam-prep');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::get('/b2b', [PartnerController::class, 'show'])->name('b2b');
Route::get('/{slug}', [PageController::class, 'legal'])
    ->where('slug', 'privacy-policy|terms-conditions|disclaimer|refund-policy')
    ->name('legal');

// Form submissions (rate limited – see AppServiceProvider)
Route::middleware('throttle:forms')->group(function () {
    Route::post('/enquiries', [EnquiryController::class, 'store'])->name('enquiries.store');
    Route::post('/b2b', [PartnerController::class, 'store'])->name('b2b.store');
});

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', fn () => response("User-agent: *\nDisallow: /admin\n\nSitemap: ".route('sitemap')."\n", 200, ['Content-Type' => 'text/plain']));

// Legacy / alternative URLs used by the previous site → permanent redirects
Route::permanentRedirect('/about', '/');
Route::permanentRedirect('/exams', '/exam-prep');
Route::permanentRedirect('/privacy', '/privacy-policy');
Route::permanentRedirect('/terms', '/terms-conditions');
Route::permanentRedirect('/terms-and-conditions', '/terms-conditions');
Route::permanentRedirect('/refund', '/refund-policy');
Route::permanentRedirect('/studienkolleg', '/germany/studienkolleg');
Route::get('/germany-{pathway}', fn (string $pathway) => redirect("/germany/{$pathway}", 301))
    ->where('pathway', 'bachelor|master|medicine');
Route::get('/study-in-germany/{pathway}', fn (string $pathway) => redirect("/germany/{$pathway}", 301))
    ->where('pathway', 'studienkolleg|bachelor|master|medicine');
Route::get('/{country}', fn (string $country) => redirect("/study-in-{$country}", 301))
    ->where('country', 'finland|netherlands|lithuania|poland|latvia|spain|italy|austria');

/*
|--------------------------------------------------------------------------
| Admin panel (lead management)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:admin-login')->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::get('enquiries/export', [Admin\EnquiryController::class, 'export'])->name('enquiries.export');
        Route::resource('enquiries', Admin\EnquiryController::class)->only(['index', 'show', 'update', 'destroy']);

        Route::get('partners/export', [Admin\PartnerApplicationController::class, 'export'])->name('partners.export');
        Route::resource('partners', Admin\PartnerApplicationController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->parameters(['partners' => 'partner']);
    });
});
