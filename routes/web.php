<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShopContextController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/* Shop pages */
Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/brands', [ShopController::class, 'brands'])->name('shop.brands');
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('shop.product');

/* Storefront context */
Route::post('/shop/mode', [ShopContextController::class, 'setMode'])->name('shop.mode');
Route::post('/shop/hub', [ShopContextController::class, 'setHub'])->name('shop.hub');

/* Cart */
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');

/* Checkout */
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/whatsapp', [CheckoutController::class, 'exportToWhatsApp'])->name('checkout.whatsapp');
