<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OldatController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\SuperUserController;
use App\Http\Controllers\UserController;


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

Route::get('/', function () { return view('login'); });
// ====================================================
//                    AUTENTIKASI
// ====================================================
Route::get('dashboard',     [AuthController::class, 'dashboard']);
Route::get('masuk',         [AuthController::class, 'index'])->name('masuk');
Route::get('daftar',  [AuthController::class, 'daftar'])->name('daftar-user');
Route::get('keluar',        [AuthController::class, 'keluar'])->name('keluar');
Route::post('post-daftar', [AuthController::class, 'postDaftar'])->name('daftar.post');
Route::post('post-masuk', [AuthController::class, 'postMasuk'])->name('masuk.post');
// ====================================================
//                    SUPER ADMIN
// ====================================================
Route::group(['middleware' => ['level:super-admin'], 'prefix' => 'super-admin', 'as' => 'super-admin.'], function () {
    Route::get('dashboard', [SuperadminController::class, 'index']);
    Route::get('level/{aksi}/{id}', [SuperadminController::class, 'showLevel']);

    Route::group(['prefix' => 'oldat', 'as' => 'oldat'], function () {
        Route::get('dashboard', [OldatController::class, 'index']);

    });

    Route::group(['prefix' => 'super-admin', 'as' => 'super-admin.'], function () {

    });


});

