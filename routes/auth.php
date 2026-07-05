<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

// Email verification routes (accessible without authentication)
Route::get('verify-email', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'show'])
    ->name('auth.verify.show');

Route::get('verify-email/{token}', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'verifyViaLink'])
    ->name('verify-email.link');

Route::post('verify-email', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])
    ->name('auth.verify.post');

Route::post('verify-email/resend', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'resend'])
    ->name('auth.verify.resend');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
