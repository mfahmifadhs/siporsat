<?php

use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
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
    Route::get('dashboard', [SuperAdminController::class, 'index']);
    Route::get('level/{aksi}/{id}', [SuperAdminController::class, 'showLevel']);
    Route::get('pegawai/{aksi}/{id}', [SuperAdminController::class, 'showEmployees']);
    Route::get('pengguna/{aksi}/{id}', [SuperAdminController::class, 'showUsers']);
    Route::get('tim-kerja/{aksi}/{id}', [SuperAdminController::class, 'showWorkteam']);
    Route::get('unit-kerja/{aksi}/{id}', [SuperAdminController::class, 'showWorkunit']);
    Route::get('kewenangan/{aksi}/{id}', [SuperAdminController::class, 'showAuthority']);
    Route::post('kewenangan/{aksi}/{id}', [SuperAdminController::class, 'showAuthority']);
    Route::post('pegawai/{aksi}/{id}', [SuperAdminController::class, 'showEmployees']);
    Route::post('unit-kerja/{aksi}/{id}', [SuperAdminController::class, 'showWorkunit']);
    Route::post('tim-kerja/{aksi}/{id}', [SuperAdminController::class, 'showWorkteam']);
    Route::post('pengguna/{aksi}/{id}', [SuperAdminController::class, 'showUsers']);
    Route::post('level/{aksi}/{id}', [SuperAdminController::class, 'showLevel']);

    Route::group(['prefix' => 'oldat', 'as' => 'oldat'], function () {
        Route::get('dashboard', [SuperAdminController::class, 'index']);
        Route::get('barang/{aksi}/{id}', [SuperAdminController::class, 'showItem']);
        Route::get('kategori-barang/{aksi}/{id}', [SuperAdminController::class, 'showCategoryItem']);
        Route::get('pengajuan/{aksi}/{id}', [SuperAdminController::class, 'submission']);

        Route::post('pengajuan/{aksi}/{id}', [SuperAdminController::class, 'submission']);
        Route::post('barang/{aksi}/{id}', [SuperAdminController::class, 'showItem']);
        Route::post('kategori-barang/{aksi}/{id}', [SuperAdminController::class, 'showCategoryItem']);

    });

    Route::group(['prefix' => 'super-admin', 'as' => 'super-admin.'], function () {

    });


});

// ====================================================
//                    ADMIN USER
// ====================================================
Route::group(['middleware' => ['level:admin-user'], 'prefix' => 'admin-user', 'as' => 'admin-user.'], function () {
    Route::get('dashboard', [AdminUserController::class, 'index']);

    Route::group(['prefix' => 'oldat', 'as' => 'oldat'], function () {
        Route::get('dashboard', [AdminUserController::class, 'index']);
        Route::get('barang/{aksi}/{id}', [AdminUserController::class, 'showItem']);

        Route::post('barang/{aksi}/{id}', [AdminUserController::class, 'showItem']);

    });
});

// ====================================================
//                    SUPER USER
// ====================================================
Route::group(['middleware' => ['level:super-user'], 'prefix' => 'super-user', 'as' => 'super-user.'], function () {
    Route::get('dashboard', [SuperUserController::class, 'index']);

    Route::group(['prefix' => 'oldat', 'as' => 'oldat'], function () {
        Route::get('dashboard', [SuperUserController::class, 'oldat'])->name('oldat');
        Route::get('laporan/{aksi}/{id}', [SuperUserController::class, 'report']);
        Route::get('/grafik', [SuperUserController::class, 'searchChartData'])->name('searchChartData');
        Route::get('rekap/{aksi}/{id}', [SuperUserController::class, 'recap']);
        Route::get('pengajuan/{aksi}/{id}', [SuperUserController::class, 'submission']);

        Route::post('pengajuan/{aksi}/{id}', [SuperUserController::class, 'submission']);

        Route::get('get-barang/{id}', [SuperUserController::class, 'getDataBarang']);
    });
});
Route::get('/tesOTP', [SuperUserController::class, 'sendOTPWhatsapp']);


