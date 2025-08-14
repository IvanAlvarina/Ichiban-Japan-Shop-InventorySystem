<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Product\ProductController;

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/register', [LoginController::class, 'store'])->name('register.store');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');

Route::get('/dashboard', [LoginController::class, 'dashboard'])->middleware('auth')
->name('dashboard');

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->prefix('products')->name('products.')->group(function () {
    Route::get('/product-list', [ProductController::class, 'index'])->name('index');
    Route::get('/create-product', [ProductController::class, 'create'])->name('create');
    Route::post('/create-product-action', [ProductController::class, 'store'])->name('store');
});