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

Route::get('/', function () { return view('index'); });
Route::get('/login', function () { return view('login'); });
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
        Route::get('dashboard', [SuperAdminController::class, 'oldat']);
        Route::get('barang/{aksi}/{id}', [SuperAdminController::class, 'showItem']);
        Route::get('kategori-barang/{aksi}/{id}', [SuperAdminController::class, 'showCategoryItem']);
        Route::get('pengajuan/{aksi}/{id}', [SuperAdminController::class, 'submission']);
        Route::get('/grafik', [SuperAdminController::class, 'searchChartData'])->name('searchChartData');

        Route::post('pengajuan/{aksi}/{id}', [SuperAdminController::class, 'submission']);
        Route::post('barang/{aksi}/{id}', [SuperAdminController::class, 'showItem']);
        Route::post('kategori-barang/{aksi}/{id}', [SuperAdminController::class, 'showCategoryItem']);

    });

    Route::group(['prefix' => 'aadb', 'as' => 'aadb.'], function () {
        Route::get('dashboard', [SuperAdminController::class, 'aadb']);
        Route::get('kendaraan/{aksi}/{id}', [SuperAdminController::class, 'vehicle']);
        Route::get('pengguna/{aksi}/{id}', [SuperAdminController::class, 'userVehicle']);
        Route::get('pengemudi/{aksi}/{id}', [SuperAdminController::class, 'driver']);
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

    Route::group(['prefix' => 'aadb', 'as' => 'aadb.'], function () {
        Route::get('dasboard', [AdminUserController::class, 'Aadb']);
        Route::get('kendaraan/{aksi}/{id}', [AdminUserController::class, 'Vehicle']);

        Route::post('kendaraan/{aksi}/{id}', [AdminUserController::class, 'Vehicle']);
    });

    Route::group(['prefix' => 'rdn', 'as' => 'rdn.'], function () {
        Route::get('dasboard', [AdminUserController::class, 'Rdn']);
        Route::get('rumah-dinas/{aksi}/{id}', [AdminUserController::class, 'House']);

        Route::post('rumah-dinas/{aksi}/{id}', [AdminUserController::class, 'House']);
    });
});

// ====================================================
//                    SUPER USER
// ====================================================
Route::group(['middleware' => ['level:super-user'], 'prefix' => 'super-user', 'as' => 'super-user.'], function () {
    Route::get('dashboard', [SuperUserController::class, 'Index']);
    Route::get('laporan-siporsat', [SuperUserController::class, 'ReportMain']);
    Route::get('sendOTP', [SuperUserController::class, 'SendOTPWhatsApp']);

    Route::group(['prefix' => 'oldat', 'as' => 'oldat'], function () {
        Route::get('dashboard', [SuperUserController::class, 'Oldat']);
        Route::get('barang/{aksi}/{id}', [SuperUserController::class, 'Items']);
        Route::get('laporan/{aksi}/{id}', [SuperUserController::class, 'ReportOldat']);
        Route::get('rekap/{aksi}/{id}', [SuperUserController::class, 'Recap']);
        Route::get('pengajuan/{aksi}/{id}', [SuperUserController::class, 'SubmissionOldat']);
        Route::get('surat/{aksi}/{id}', [SuperUserController::class, 'LetterOldat']);

        Route::post('pengajuan/{aksi}/{id}', [SuperUserController::class, 'SubmissionOldat']);
        Route::post('surat/{aksi}/{id}', [SuperUserController::class, 'Letter']);

        Route::get('get-barang/{id}', [SuperUserController::class, 'JsonItems']);
        Route::get('/grafik', [SuperUserController::class, 'SearchChartDataOldat']);
        Route::get('/grafik-laporan', [SuperUserController::class, 'SearchChartReportOldat']);
        Route::get('QiscusOTP', [SuperUserController::class, 'OTPQiscus']);
    });

    Route::group(['prefix' => 'aadb', 'as' => 'aadb.'], function () {
        Route::get('dashboard', [SuperUserController::class, 'Aadb']);
        Route::get('kendaraan/{aksi}/{id}', [SuperUserController::class, 'Vehicle']);
        Route::get('laporan/{aksi}/{id}', [SuperUserController::class, 'ReportAadb']);
        Route::get('rekapitulasi', [SuperUserController::class, 'RecapAadb']);
        Route::get('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionAadb']);
        Route::get('surat/{aksi}/{id}', [SuperUserController::class, 'LetterAadb']);

        Route::post('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionAadb']);
        Route::post('kendaraan/{aksi}/{id}', [SuperUserController::class, 'Vehicle']);
        Route::get('surat/{aksi}/{id}', [SuperUserController::class, 'LetterAadb']);

        Route::get('dashboard/{aksi}', [SuperUserController::class, 'SearchChartDataAadb']);
        Route::get('/grafik-laporan', [SuperUserController::class, 'SearchChartReportAadb']);
    });

    Route::group(['prefix' => 'ppk', 'as' => 'ppk'], function () {
        Route::get('{modul}/pengajuan/{aksi}/{id}', [SuperUserController::class, 'SubmissionPPK']);
        Route::post('{modul}/pengajuan/{aksi}/{id}', [SuperUserController::class, 'SubmissionPPK']);
    });

});


