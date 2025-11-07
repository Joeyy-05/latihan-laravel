<?php

use App\Http\Controllers\AuthController;
// use App\Http\Controllers\HomeController; // <-- Dihapus
use App\Http\Controllers\CatatanKeuanganController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::group(['prefix' => 'app', 'middleware' => 'check.auth'], function () {
    
    // --- Rute Todo Dihapus ---
    // Route::get('/home', [HomeController::class, 'index'])->name('app.home');
    // Route::get('/todos/{todo_id}', [HomeController::class, 'todoDetail'])->name('app.todos.detail');
    // --- Batas Rute Todo ---

    // Rute Catatan Keuangan (Sekarang menjadi rute utama aplikasi)
    Route::get('/catatan-keuangan', [CatatanKeuanganController::class, 'index'])->name('app.catatan-keuangan.index');
});

Route::get('/', function () {
    // Arahkan halaman utama ke Catatan Keuangan, bukan 'app.home'
    return redirect()->route('app.catatan-keuangan.index');
});     