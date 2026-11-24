<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RestaurantController::class, 'home'])->name('home');
Route::get('/restoran', [RestaurantController::class, 'restaurants'])->name('restaurants.list');
Route::get('/coffee', [RestaurantController::class, 'coffee'])->name('coffee.list');
Route::get('/peta', [RestaurantController::class, 'map'])->name('restaurants.map');

Route::get('/tambah', [RestaurantController::class, 'create'])->name('restaurants.create');
Route::post('/tempat', [RestaurantController::class, 'store'])->name('restaurants.store');
Route::get('/tempat/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');
Route::post('/tempat/{restaurant}/menu', [ProductController::class, 'store'])->name('products.store');
Route::get('/tempat/{restaurant}/menu/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/tempat/{restaurant}/menu/{product}', [ProductController::class, 'update'])->name('products.update');
Route::get('/tempat/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('restaurants.edit');
Route::put('/tempat/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
Route::delete('/tempat/{restaurant}', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');
Route::delete('/tempat/{restaurant}/menu/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// Catatan: tambahkan middleware('auth') pada rute tambah dan menu sebelum dipakai publik.
