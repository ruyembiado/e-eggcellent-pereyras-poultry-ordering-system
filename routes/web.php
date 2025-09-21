<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;

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

Route::get('/', [AuthController::class, 'index']);
Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/shop', [ShopController::class, 'index']);
Route::get('/contact-us', [AuthController::class, 'contact']);
Route::get('/login-auth', [AuthController::class, 'loginPage'])->name('login.page');
Route::get('/register-auth', [AuthController::class, 'registerPage'])->name('register.page');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Product
    Route::get('/products/all', [ProductController::class, 'index']);
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

    // User
    Route::get('/users', [UserController::class, 'index']);
    Route::put('activation-status/{id}', [UserController::class, 'user_activation'])->name('user.activation');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update.profile');

    // Cart
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Order
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.view');
    Route::get('/order/received/{id}', [OrderController::class, 'receivedOrder'])->name('order.received');
    Route::get('/create-order', [OrderController::class, 'showWalkInOrderForm'])->name('order.walkin');
    Route::post('/create-order', [OrderController::class, 'createWalkInOrder'])->name('order.walkin.store');

    // Request Order (admin side)
    Route::get('/request-orders', [OrderController::class, 'index'])->name('request.index');
    Route::post('/request-order/accept', [OrderController::class, 'accept'])->name('order.accept');
    Route::post('/request-order/decline', [OrderController::class, 'decline'])->name('order.decline');

    // Report
    Route::get('/reports', [ReportController::class, 'index'])->name('report.index');
    Route::get('/report/daily', [ReportController::class, 'dailyReport'])->name('daily.report');
    Route::get('/reports/weekly', [ReportController::class, 'weeklyReport'])->name('weekly.report');
    Route::get('/reports/monthly', [ReportController::class, 'monthlyReport'])->name('monthly.report');
    Route::get('/reports/yearly', [ReportController::class, 'yearlyReport'])->name('yearly.report');

    // Rate
    Route::post('/rate-order', [RatingController::class, 'store'])->name('rate.store');
});
