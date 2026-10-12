<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\StaffAccountController;
use App\Http\Controllers\Staff\AuditLogController;
use App\Http\Controllers\QrCodeController;


Route::get('/', function () {
    return view('landing');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/qrcode', [QrCodeController::class, 'show'])->name('qrcode.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes available to any authenticated staff member (staff or supervisor)
Route::middleware(['auth', 'staffonly', 'passwordchange'])->prefix('staff')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('staff.dashboard');
    Route::get('/orders/{order}', [DashboardController::class, 'show'])->name('staff.show');
    Route::get('/orders/{order}/preview', [DashboardController::class, 'preview'])->name('staff.preview');
    Route::get('/orders/{order}/file', [DashboardController::class, 'file'])->middleware('signed')->name('staff.file');
    Route::get('/orders/{order}/print', [DashboardController::class, 'print'])->name('staff.print');
    Route::post('/orders/{order}/status', [DashboardController::class, 'updateStatus'])->name('staff.update');
});

// Routes restricted to supervisors only: reports and staff account management
Route::middleware(['auth', 'supervisoronly', 'passwordchange'])->prefix('staff')->group(function () {
    Route::get('/reports', [DashboardController::class, 'reports'])->name('staff.reports');
    Route::get('/reports/{date}', [DashboardController::class, 'reportDetail'])->name('staff.report.detail');

    Route::get('/audit-log', [AuditLogController::class, 'index'])->name('staff.audit');

    Route::get('/accounts', [StaffAccountController::class, 'index'])->name('staff.accounts.index');
    Route::get('/accounts/create', [StaffAccountController::class, 'create'])->name('staff.accounts.create');
    Route::post('/accounts', [StaffAccountController::class, 'store'])->name('staff.accounts.store');
    Route::patch('/accounts/{account}/reset-password', [StaffAccountController::class, 'resetPassword'])->name('staff.accounts.reset-password');
    Route::delete('/accounts/{account}', [StaffAccountController::class, 'destroy'])->name('staff.accounts.destroy');
});

Route::get('/order', [OrderController::class, 'create'])->name('orders.create');
Route::post('/order', [OrderController::class, 'store'])->middleware('throttle:10,1')->name('orders.store');
Route::get('/order/success', [OrderController::class, 'success'])->name('orders.success');
Route::get('/track', [OrderController::class, 'track'])->middleware('throttle:20,1')->name('orders.track');


require __DIR__.'/auth.php';