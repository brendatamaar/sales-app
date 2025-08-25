<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BobotController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SalperController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('dashboard');
    })->name('home');


    Route::prefix('stores')->group(function () {
        Route::get('/search', [StoreController::class, 'search'])->name('stores.search');
    });
    Route::get('/items/search', [ProductController::class, 'search'])
        ->name('items.search');

    Route::get('/salpers/search', [SalperController::class, 'search'])->name('salpers.search');

    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
    Route::get('/deals/{id}', [DealController::class, 'getDeal'])->name('deals.api.show');

    Route::resource('salpers', SalperController::class);
    Route::resource('deals', DealController::class);
    Route::resource('products', ProductController::class);
    Route::resource('dokumen', DokumenController::class);
    Route::resource('properties', PropertyController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('users', UserController::class);
    Route::resource('bobot', BobotController::class);
    Route::resource('point', PointController::class);

});