<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;

/*
|--------------------------------------------------------------------------
| SnapStudio - Photobooth Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', [PhotoController::class, 'home'])->name('home');

// Galeri foto
Route::get('/galeri', [PhotoController::class, 'gallery'])->name('gallery');

// Photobooth studio
Route::get('/photobooth', [PhotoController::class, 'photobooth'])->name('photobooth');

// API: Simpan foto
Route::post('/photos', [PhotoController::class, 'store'])->name('photos.store');

// API: Hapus foto
Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');
