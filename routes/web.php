<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\InternetCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('backups', BackupController::class);

    Route::get('/internet-check', [InternetCheckController::class, 'index'])->name('internet.index');
    Route::post('/internet-check/test', [InternetCheckController::class, 'testConnection'])->name('internet.test');
    Route::post('/internet-check/notify/email', [InternetCheckController::class, 'sendEmailReport'])->name('internet.notify.email');
});

require __DIR__.'/auth.php';