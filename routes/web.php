<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandingPageController;

Route::resource('categories', CategoriesController::class);
Route::resource('products', ProductsController::class);
Route::resource('orders', OrdersController::class);
Route::resource('payments', PaymentsController::class);
Route::resource('sellers', SellerController::class);
Route::resource('customers', CustomerController::class);  
Route::resource('carts', CartController::class);
Route::resource('users', UserController::class);



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landing_page');
})->name('landing_page');


// mengelola data produk
Route::prefix('/data-produk')->name('data_produk.')->group(function() {
    Route::get('/data', [ProductsController::class, 'index'])->name('data');
    Route::get('/tambah', [ProductsController::class, 'create'])->name('tambah');
    Route::post('/tambah/proses', [ProductsController::class, 'store'])->name('tambah.proses');
    Route::get('/ubah/{id}', [ProductsController::class, 'edit'])->name('ubah');
    Route::patch('/ubah/{id}/proses', [ProductsController::class, 'update'])->name('ubah.proses');
    Route::delete('/hapus/{id}', [ProductsController::class, 'destroy'])->name('hapus');
    Route::patch('/ubah/stock/{id}', [ProductsController::class, 'updateStock'])->name('ubah.stock');
});

// kelola akun
Route::prefix('/kelola-akun')->name('kelola_akun.')->group(function() {
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/tambah', [UserController::class, 'create'])->name('tambah');
    Route::post('/tambah/proses', [UserController::class, 'store'])->name('tambah.proses');
    Route::get('/ubah/{id}', [UserController::class, 'edit'])->name('ubah');
    Route::patch('/ubah/{id}/proses', [UserController::class, 'update'])->name('ubah.proses');
    Route::delete('/hapus/{id}', [UserController::class, 'destroy'])->name('hapus');

});

// // Seller Routes (requires seller middleware)
// Route::middleware('seller')->prefix('seller')->group(function () {
//     Route::get('/', [SellerController::class, 'index'])->name('seller.index');
//     Route::resource('products', SellerController::class)->except(['show']);
//     Route::get('orders', [SellerController::class, 'viewOrders'])->name('seller.orders');
//     Route::get('payments', [SellerController::class, 'viewPayments'])->name('seller.payments');
// });

// // Customer Routes
// Route::prefix('customer')->group(function () {
//     Route::get('browse', [CustomerController::class, 'browseProducts'])->name('customer.browse');
//     Route::get('product/{id}', [CustomerController::class, 'viewProduct'])->name('customer.product');
//     Route::post('cart/add/{productId}', [CustomerController::class, 'addToCart'])->name('cart.add');
//     Route::get('cart', [CustomerController::class, 'viewCart'])->name('cart.view');
//     Route::get('checkout', [CustomerController::class, 'checkout'])->name('cart.checkout');
//     Route::post('order', [CustomerController::class, 'placeOrder'])->name('order.place');
//     Route::get('orders', [CustomerController::class, 'viewOrderHistory'])->name('customer.orders');
// });

// // Cart Routes
// Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
// Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
// Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
// Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

