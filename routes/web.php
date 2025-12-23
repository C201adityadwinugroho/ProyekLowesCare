<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/manajemen-pesanan', function () {
    // Nantinya data $orders akan diambil dari database di sini
    $orders = []; // Sementara kosong
    return view('manajemen-pesanan', compact('orders'));
})->middleware(['auth'])->name('pesanan.index');

Route::get('/kebutuhan', function () {
    return view('kebutuhan');
})->middleware(['auth'])->name('kebutuhan.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
