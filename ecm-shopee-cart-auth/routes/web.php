<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UrProductController;


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
    return view('welcome');
});


Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login',  [AuthController::class,  'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard',  [AdminController::class,  'dashboard'])->name('admin.dashboard');

    Route::resource('/admin/products', ProductController::class, [
        'as' => 'admin' // Đặt tiền tố 'admin' cho tất cả các route của products 
    ]);
    Route::resource('/admin/categories', CategoryController::class, [
        'as' => 'admin' // Đặt tiền tố 'admin' cho tất cả các route của products 
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});