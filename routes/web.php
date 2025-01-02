<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityInvitationController;
use App\Http\Controllers\ActivityMemberController;
use App\Http\Controllers\QRCodeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/token', function () {
    $token = csrf_token();

    return response()->json([
        'token' => $token,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/profile/search', [ProfileController::class, 'search'])->name('profile.search');
    Route::get('/profile/qrcode', [QRCodeController::class, 'index'])->name('profile.qrcode.index');

    Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('/activity/create', [ActivityController::class, 'create'])->name('activity.create');
    Route::get('/activity/{activity}', [ActivityController::class, 'show'])->name('activity.show');
    Route::get('/activity/{activity}/scan', [ActivityController::class, 'scan'])->name('activity.scan');
    Route::get('/activity/{activity}/edit', [ActivityController::class, 'edit'])->name('activity.edit');
    Route::post('/activity/create', [ActivityController::class, 'store'])->name('activity.store');
    Route::patch('/activity/{activity}', [ActivityController::class, 'update'])->name('activity.update');
    Route::delete('/activity/{activity}', [ActivityController::class, 'destroy'])->name('activity.destroy');

    Route::get('/activity/{activity}/invitation', [ActivityInvitationController::class, 'index'])->name('activity.invitation.index');
    Route::post('/activity/{activity}/invitation', [ActivityInvitationController::class, 'store'])->name('activity.invitation.store');
    Route::delete('/activity/{activity}/invitation/{invitation}', [ActivityInvitationController::class, 'destroy'])->name('activity.invitation.destroy');
    
    Route::get('/activity/{activity}/member', [ActivityMemberController::class, 'show'])->name('activity.member.show');
    Route::post('/activity/{activity}/member/join', [ActivityMemberController::class, 'join'])->name('activity.member.join');
    Route::post('/activity/{activity}/member/leave', [ActivityMemberController::class, 'leave'])->name('activity.member.leave');
});

require __DIR__.'/auth.php';
