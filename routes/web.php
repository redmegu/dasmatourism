<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttractionController;
use App\Http\Controllers\BusinessDirectoryController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\StoryModeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AttractionController as AdminAttractionController;
use App\Http\Controllers\Admin\BusinessController as AdminBusinessController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BusinessCategoryController as AdminBusinessCategoryController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\StoryModeController as AdminStoryModeController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\LandmarkSuggestionController as AdminLandmarkSuggestionController;
use App\Http\Controllers\Admin\HeroSlideController as AdminHeroSlideController;

// Business Owner Controllers
use App\Http\Controllers\BusinessOwner\DashboardController as BusinessOwnerDashboardController;
use App\Http\Controllers\BusinessOwner\BusinessProfileController;
use App\Http\Controllers\BusinessOwner\PromotionController as BusinessOwnerPromotionController;
use App\Http\Controllers\BusinessOwner\AnalyticsController as BusinessOwnerAnalyticsController;

// User Controllers
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\LandmarkSuggestionController as UserLandmarkSuggestionController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Attractions
Route::get('/attractions', [AttractionController::class, 'index'])->name('attractions.index');
Route::get('/attractions/{slug}', [AttractionController::class, 'show'])->name('attractions.show');

// Business Directory
Route::get('/businesses', [BusinessDirectoryController::class, 'index'])->name('businesses.index');
Route::get('/businesses/{slug}', [BusinessDirectoryController::class, 'show'])->name('businesses.show');

// Map
Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/api/map/markers', [MapController::class, 'getMarkers'])->name('map.markers');

// Story Mode
Route::get('/story-mode', [StoryModeController::class, 'index'])->name('story-mode.index');
Route::middleware(['auth', \App\Http\Middleware\EnsureEmailIsVerified::class])->group(function () {
    Route::get('/story-mode/start', [StoryModeController::class, 'start'])->name('story-mode.start');
    Route::get('/story-mode/chapter/{chapter}', [StoryModeController::class, 'showChapter'])->name('story-mode.chapter');
    Route::post('/story-mode/choice', [StoryModeController::class, 'makeChoice'])->name('story-mode.choice');
});

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// --- Core Public Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // User Profile and Protected Routes (requires email verification)
    Route::middleware(\App\Http\Middleware\EnsureEmailIsVerified::class)->group(function () {
        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::prefix('profile')->name('user.profile.')->group(function () {
            Route::get('/', [UserProfileController::class, 'show'])->name('show');
            Route::get('/edit', [UserProfileController::class, 'edit'])->name('edit');
            Route::put('/', [UserProfileController::class, 'update'])->name('update');
        });

        // User Bookmarks
        Route::prefix('bookmarks')->name('user.bookmarks.')->group(function () {
            Route::get('/', [UserProfileController::class, 'bookmarks'])->name('index');
            Route::post('/{type}/{id}', [UserProfileController::class, 'toggleBookmark'])->name('toggle');
        });

        // User Reviews
        Route::prefix('my-reviews')->name('user.reviews.')->group(function () {
            Route::get('/', [UserProfileController::class, 'reviews'])->name('index');
        });

        // Landmark Suggestions
        Route::prefix('suggestions')->name('user.suggestions.')->group(function () {
            Route::get('/', [UserLandmarkSuggestionController::class, 'index'])->name('index');
            Route::get('/create', [UserLandmarkSuggestionController::class, 'create'])->name('create');
            Route::post('/', [UserLandmarkSuggestionController::class, 'store'])->name('store');
            Route::get('/{suggestion}', [UserLandmarkSuggestionController::class, 'show'])->name('show');
            Route::get('/{suggestion}/edit', [UserLandmarkSuggestionController::class, 'edit'])->name('edit');
            Route::put('/{suggestion}', [UserLandmarkSuggestionController::class, 'update'])->name('update');
            Route::delete('/{suggestion}', [UserLandmarkSuggestionController::class, 'destroy'])->name('destroy');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Administrator Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Hero Carousel Slides
    Route::get('/hero-slides', [AdminHeroSlideController::class, 'index'])->name('hero-slides.index');
    Route::post('/hero-slides', [AdminHeroSlideController::class, 'store'])->name('hero-slides.store');
    Route::put('/hero-slides/{heroSlide}', [AdminHeroSlideController::class, 'update'])->name('hero-slides.update');
    Route::delete('/hero-slides/{heroSlide}', [AdminHeroSlideController::class, 'destroy'])->name('hero-slides.destroy');
    Route::patch('/hero-slides/{heroSlide}/toggle', [AdminHeroSlideController::class, 'toggleActive'])->name('hero-slides.toggle');
    Route::post('/hero-slides/reorder', [AdminHeroSlideController::class, 'reorder'])->name('hero-slides.reorder');

    Route::resource('announcements', \App\Http\Controllers\Admin\AnnouncementController::class)->except(['show']);

    // Attractions
    Route::resource('attractions', AdminAttractionController::class);
    // Place ONLY this inside the admin group, NOT both:
    Route::delete('attractions/images/{id}', [AdminAttractionController::class, 'deleteImage'])
        ->name('attractions.images.delete');
    Route::get('/database/backup', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'download'])
    ->name('database.backup');
    Route::post('/reviews/approve-all', 
    [\App\Http\Controllers\Admin\ReviewController::class, 'approveAll']
)->name('reviews.approve-all');
    Route::post('/reviews/reject-all',
    [\App\Http\Controllers\Admin\ReviewController::class, 'rejectAll']
)->name('reviews.reject-all');
    Route::post('/reviews/bulk-approve', [AdminReviewController::class, 'bulkApprove'])
    ->name('reviews.bulk-approve');

Route::post('/reviews/bulk-reject', [AdminReviewController::class, 'bulkReject'])
    ->name('reviews.bulk-reject');





    // Businesses
    Route::resource('businesses', AdminBusinessController::class)->except(['edit', 'update']);
    Route::post('/businesses/{business}/status', [AdminBusinessController::class, 'updateStatus'])->name('businesses.status');
    Route::post('/businesses/{business}/verify', [AdminBusinessController::class, 'toggleVerification'])->name('businesses.verify');

    // Business Permit Management (ADD THESE NEW ROUTES)
    Route::post('/businesses/{business}/permit/verify', [AdminBusinessController::class, 'verifyPermit'])->name('businesses.permit.verify');
    Route::post('/businesses/{business}/permit/reject', [AdminBusinessController::class, 'rejectPermit'])->name('businesses.permit.reject');

    // Categories
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'edit']);

    // Business Categories
    Route::resource('business-categories', AdminBusinessCategoryController::class)->except(['create', 'show', 'edit']);

    // Reviews
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Users
    Route::resource('users', AdminUserController::class);

    // Promotions
    Route::resource('promotions', AdminPromotionController::class);

    // Story Mode
    Route::resource('story-chapters', AdminStoryModeController::class);
    Route::post('/story-chapters/{chapter}/choices', [AdminStoryModeController::class, 'storeChoice'])->name('story-chapters.choices.store');
    Route::delete('/story-chapters/choices/{choice}', [AdminStoryModeController::class, 'destroyChoice'])->name('story-chapters.choices.destroy');

    // Landmark Suggestions
    Route::get('/suggestions', [AdminLandmarkSuggestionController::class, 'index'])->name('suggestions.index');
    Route::get('/suggestions/{suggestion}', [AdminLandmarkSuggestionController::class, 'show'])->name('suggestions.show');
    Route::post('/suggestions/{suggestion}/approve', [AdminLandmarkSuggestionController::class, 'approve'])->name('suggestions.approve');
    Route::post('/suggestions/{suggestion}/reject', [AdminLandmarkSuggestionController::class, 'reject'])->name('suggestions.reject');
    Route::get('/suggestions/{suggestion}/convert', [AdminLandmarkSuggestionController::class, 'convertToAttraction'])->name('suggestions.convert');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/system-usage', [AdminReportController::class, 'systemUsage'])->name('system-usage');
        Route::get('/attractions', [AdminReportController::class, 'attractionAnalytics'])->name('attractions');
        Route::get('/businesses', [AdminReportController::class, 'businessAnalytics'])->name('businesses');
        Route::get('/users', [AdminReportController::class, 'userDemographics'])->name('users');

        // PDF exports
        Route::get('/system-usage/pdf', [AdminReportController::class, 'systemUsagePdf'])->name('system-usage.pdf');
        Route::get('/attractions/pdf', [AdminReportController::class, 'attractionAnalyticsPdf'])->name('attractions.pdf');
        Route::get('/businesses/pdf', [AdminReportController::class, 'businessAnalyticsPdf'])->name('businesses.pdf');
        Route::get('/users/pdf', [AdminReportController::class, 'userDemographicsPdf'])->name('users.pdf');
    });

    // Activity Logs
    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('index');
        Route::get('/{activityLog}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('show');
        Route::delete('/{activityLog}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->name('destroy');
        Route::post('/clear', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clear'])->name('clear');
    });

    // Email Verification Codes (Development Helper)
    Route::get('/email-verification-codes', function () {
        $tokens = \App\Models\EmailVerificationToken::with('user')
            ->latest()
            ->paginate(20);
        return view('admin.email-verification-codes', compact('tokens'));
    })->name('email-verification-codes');
});

/*
|--------------------------------------------------------------------------
| Business Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:business_owner'])->prefix('business-owner')->name('business-owner.')->group(function () {
    Route::get('/dashboard', [BusinessOwnerDashboardController::class, 'index'])->name('dashboard');

    // Business Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [BusinessProfileController::class, 'show'])->name('show');
        Route::get('/create', [BusinessProfileController::class, 'create'])->name('create');
        Route::post('/', [BusinessProfileController::class, 'store'])->name('store');
        Route::get('/edit', [BusinessProfileController::class, 'edit'])->name('edit');
        Route::put('/', [BusinessProfileController::class, 'update'])->name('update');
        Route::delete('/images/{id}', [BusinessProfileController::class, 'deleteImage'])->name('images.delete');

        // Preview/Status Page
        Route::get('/preview', [BusinessProfileController::class, 'preview'])->name('preview');
    });

    // Promotions
    Route::resource('promotions', BusinessOwnerPromotionController::class);

    // Reviews
    Route::get('/reviews', [BusinessOwnerDashboardController::class, 'reviews'])->name('reviews.index');

    // Analytics
    Route::get('/analytics', [BusinessOwnerAnalyticsController::class, 'index'])->name('analytics');
});

require __DIR__ . '/auth.php';
