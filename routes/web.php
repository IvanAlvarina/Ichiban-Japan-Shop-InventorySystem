<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Customer\CustomerController;

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/register', [LoginController::class, 'store'])->name('register.store');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');

Route::get('/dashboard', [LoginController::class, 'dashboard'])->middleware('auth')
->name('dashboard');

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

//products
Route::middleware(['auth'])->prefix('products')->name('products.')->group(function () {
    Route::get('/product-list', [ProductController::class, 'index'])->name('index');
    Route::get('/json', [ProductController::class, 'getProducts'])->name('json');
    Route::get('/create-product', [ProductController::class, 'create'])->name('create');
    Route::post('/create-product-action', [ProductController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [ProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
});

//customers
Route::middleware(['auth'])->prefix('customers')->name('customers.')->group(function () {
    Route::get('/customer-list', [CustomerController::class, 'index'])->name('index');
    Route::get('/json', [CustomerController::class, 'getCustomers'])->name('json');
    Route::get('/create-customer', [CustomerController::class, 'create'])->name('create');
    Route::post('/create-customer-action', [CustomerController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [CustomerController::class, 'update'])->name('update');
    Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
});
