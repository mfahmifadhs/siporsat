<?php

use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperUserController;
use App\Http\Controllers\MailController;
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
Route::get('/send-email',[MailController::class, 'index']);
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
    Route::get('jabatan', [AdminUserController::class, 'JsonJabatan']);
    Route::get('profil/{aksi}/{id}', [AdminUserController::class, 'Profile']);
    Route::get('verif/{aksi}/{id}', [AdminUserController::class, 'Verification']);

    Route::post('profil/{aksi}/{id}', [AdminUserController::class, 'Profile']);
    Route::post('select2/{aksi}', [AdminUserController::class, 'Select2']);
    Route::post('verif/{aksi}/{id}', [AdminUserController::class, 'Verification'])->middleware('2fa');

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

    Route::group(['prefix' => 'atk', 'as' => 'atk.'], function() {
        Route::get('barang/{aksi}/{id}', [AdminUserController::class, 'OfficeStationery']);
        Route::get('usulan/{aksi}/{id}', [AdminUserController::class, 'SubmissionAtk']);
        Route::get('surat/{aksi}/{id}', [AdminUserController::class, 'LetterAtk']);

        Route::post('usulan/{aksi}/{id}', [AdminUserController::class, 'SubmissionAtk']);
    });

    Route::group(['prefix' => 'rdn', 'as' => 'rdn.'], function () {
        Route::get('dasboard', [AdminUserController::class, 'Rdn']);
        Route::get('rumah-dinas/{aksi}/{id}', [AdminUserController::class, 'OfficialResidence']);

        Route::post('rumah-dinas/{aksi}/{id}', [AdminUserController::class, 'OfficialResidence']);
    });
});

// ====================================================
//                    SUPER USER
// ====================================================
Route::group(['middleware' => ['level:super-user'], 'prefix' => 'super-user', 'as' => 'super-user.'], function () {
    Route::get('dashboard', [SuperUserController::class, 'Index']);
    Route::get('profil/{aksi}/{id}', [SuperUserController::class, 'Profile']);
    Route::get('laporan-siporsat', [SuperUserController::class, 'ReportMain']);
    Route::get('sendOTP', [SuperUserController::class, 'SendOTPWhatsApp']);
    Route::get('verif/{aksi}/{id}', [SuperUserController::class, 'Verification']);

    Route::post('profil/{aksi}/{id}', [SuperUserController::class, 'Profile']);
    Route::post('verif/{aksi}/{id}', [SuperUserController::class, 'Verification'])->middleware('2fa');

    // oldat
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
        // Route::get('QiscusOTP', [SuperUserController::class, 'OTPQiscus']);
        Route::get('/select2/{aksi}', [SuperUserController::class, 'Select2Oldat']);
    });

    // aadb
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
        Route::post('/select2/{aksi}', [SuperUserController::class, 'Select2Aadb']);
    });

    // atk
    Route::group(['prefix' => 'atk', 'as' => 'atk.'], function() {
        Route::get('dashboard', [SuperUserController::class, 'Atk']);
        Route::get('barang/{aksi}/{id}', [SuperUserController::class, 'OfficeStationery']);
        Route::get('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionAtk']);
        Route::get('/select2/{aksi}/{id}', [SuperUserController::class, 'Select2Atk']);
        Route::get('surat/{aksi}/{id}', [SuperUserController::class, 'LetterAtk']);

        Route::post('surat/{aksi}/{id}', [SuperUserController::class, 'LetterAtk']);
        Route::post('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionAtk']);
        Route::post('/select2/{aksi}/{id}', [SuperUserController::class, 'Select2Atk']);
        Route::post('/select2-dashboard/{aksi}/{id}', [SuperUserController::class, 'Select2AtkDashboard']);

        Route::get('/grafik', [SuperUserController::class, 'SearchChartDataAtk']);
    });

    // gedung dan bangunan
    Route::group(['prefix' => 'gdn', 'as' => 'gdn'], function() {
        Route::get('dashboard', [SuperUserController::class, 'Gdn']);
        Route::get('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionGdn']);
        Route::get('surat/{aksi}/{id}', [SuperUserController::class, 'LetterGdn']);

        Route::post('surat/{aksi}/{id}', [SuperUserController::class, 'LetterGdn']);
        Route::post('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionGdn']);
    });

    // rumah dinas
    Route::group(['prefix' => 'rdn', 'as' => 'rdn.'], function () {
        Route::get('dashboard', [SuperUserController::class, 'Rdn']);
        Route::get('rumah-dinas/{aksi}/{id}', [SuperUserController::class, 'OfficialResidence']);
        Route::get('laporan/{aksi}/{id}', [SuperUserController::class, 'ReportRdn']);
        Route::get('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionRdn']);
        Route::get('surat/{aksi}/{id}', [SuperUserController::class, 'LetterRdn']);

        Route::post('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionRdn']);
        Route::post('rumah-dinas/{aksi}/{id}', [SuperUserController::class, 'OfficialResidence']);
        Route::get('surat/{aksi}/{id}', [SuperUserController::class, 'LetterRdn']);

        Route::get('/grafik', [SuperUserController::class, 'SearchChartDataRdn']);
        Route::get('/grafik-laporan', [SuperUserController::class, 'SearchChartReportRdn']);
    });


    // ppk
    Route::group(['prefix' => 'ppk', 'as' => 'ppk'], function () {
        Route::get('{modul}/{tujuan}/{aksi}/{id}', [SuperUserController::class, 'SubmissionPPK']);

        Route::post('{modul}/{tujuan}/{aksi}/{id}', [SuperUserController::class, 'SubmissionPPK']);
    });

});


// ====================================================
//                    USER
// ====================================================
Route::group(['middleware' => ['level:user'], 'prefix' => 'unit-kerja', 'as' => 'unit-kerja.'], function () {
    Route::get('dashboard', [UserController::class, 'Index'])->name('user.index');
    Route::get('profil/{aksi}/{id}', [UserController::class, 'Profile']);
    Route::get('gdn', [UserController::class, 'Building']);
    Route::get('verif/{aksi}/{id}', [UserController::class, 'Verification']);
    Route::get('surat/{aksi}/{id}', [UserController::class, 'Letter']);


    Route::post('profil/{aksi}/{id}', [UserController::class, 'Profile']);
    Route::post('verif/{aksi}/{id}', [UserController::class, 'Verification'])->middleware('2fa');

    Route::group(['prefix' => 'gdn', 'as' => 'gdn'], function () {
        Route::get('usulan/{aksi}/{id}', [UserController::class, 'SubmissionGdn']);
        Route::get('js/{aksi}/{id}', [UserController::class, 'JsGdn']);

        Route::post('usulan/{aksi}/{id}', [UserController::class, 'SubmissionGdn']);
    });

});

