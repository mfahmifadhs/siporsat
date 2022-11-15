<?php

use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
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
Route::get('/surat/{modul}/{id}', [Controller::class, 'Links']);
Route::get('/usulan/{modul}/{id}', [Controller::class, 'Letters']);
// ====================================================
//                    AUTENTIKASI
// ====================================================
Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::get('masuk', [AuthController::class, 'index'])->name('masuk');
Route::get('daftar', [AuthController::class, 'daftar'])->name('daftar-user');
Route::get('keluar', [AuthController::class, 'keluar'])->name('keluar');
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
    Route::post('select2/{aksi}/{id}', [SuperAdminController::class, 'Select2User']);

    Route::group(['prefix' => 'oldat', 'as' => 'oldat'], function () {
        Route::get('dashboard', [SuperAdminController::class, 'Oldat']);
        Route::get('/grafik', [SuperAdminController::class, 'SearchChartDataOldat']);
        Route::get('usulan/{aksi}/{id}', [SuperAdminController::class, 'SubmissionOldat']);
        Route::get('barang/{aksi}/{id}', [SuperAdminController::class, 'Items']);

        Route::post('barang/{aksi}/{id}', [SuperAdminController::class, 'Items']);
        Route::post('/select2-dashboard/{aksi}/{id}', [SuperAdminController::class, 'Select2OldatDashboard']);
    });

    Route::group(['prefix' => 'aadb', 'as' => 'aadb.'], function () {
        Route::get('dashboard', [SuperAdminController::class, 'Aadb']);
        Route::get('/grafik', [SuperAdminController::class, 'SearchChartDataAadb']);
        Route::get('usulan/{aksi}/{id}', [SuperAdminController::class, 'SubmissionAadb']);
        Route::get('kendaraan/{aksi}/{id}', [SuperAdminController::class, 'Vehicle']);

        Route::post('kendaraan/{aksi}/{id}', [SuperAdminController::class, 'Vehicle']);
        Route::post('/select2-dashboard/{aksi}/{id}', [SuperAdminController::class, 'Select2AadbDashboard']);
    });

    Route::group(['prefix' => 'atk', 'as' => 'atk.'], function () {
        Route::get('dashboard', [SuperAdminController::class, 'Atk']);
        Route::get('/grafik', [SuperAdminController::class, 'SearchChartDataAtk']);
        Route::get('usulan/{aksi}/{id}', [SuperAdminController::class, 'SubmissionAtk']);
        Route::get('barang/{aksi}/{id}', [SuperAdminController::class, 'OfficeStationery']);

        Route::post('barang/{aksi}/{id}', [SuperAdminController::class, 'OfficeStationery']);
        Route::post('/select2-dashboard/{aksi}/{id}', [SuperAdminController::class, 'Select2AtkDashboard']);
    });

    Route::group(['prefix' => 'gdn', 'as' => 'gdn.'], function () {
        Route::get('dashboard/{aksi}/{id}', [SuperAdminController::class, 'Gdn']);

        Route::post('/select2-dashboard/{aksi}/{id}', [SuperAdminController::class, 'Select2AtkDashboard']);
    });

    Route::group(['prefix' => 'rdn', 'as' => 'rdn.'], function () {
        Route::get('dashboard', [SuperAdminController::class, 'Rdn']);
        Route::get('/grafik', [SuperAdminController::class, 'SearchChartDataRdn']);
        Route::get('usulan/{aksi}/{id}', [SuperAdminController::class, 'SubmissionRdn']);
        Route::get('rumah-dinas/{aksi}/{id}', [SuperAdminController::class, 'OfficialResidence']);

        Route::post('rumah-dinas/{aksi}/{id}', [SuperAdminController::class, 'OfficialResidence']);
        Route::post('/select2-dashboard/{aksi}/{id}', [SuperAdminController::class, 'Select2RdnDashboard']);
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
        Route::get('/select2/{aksi}/{id}', [AdminUserController::class, 'Select2Atk']);

        Route::post('barang/{aksi}/{id}', [AdminUserController::class, 'OfficeStationery']);
        Route::post('/select2/{aksi}/{id}', [AdminUserController::class, 'Select2Atk']);
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
        Route::get('/select2/{aksi}', [SuperUserController::class, 'Select2Oldat']);
        Route::get('/select2-dashboard/{aksi}/{id}', [SuperUserController::class, 'Select2OldatDashboard']);

        Route::post('/select2-dashboard/{aksi}/{id}', [SuperUserController::class, 'Select2OldatDashboard']);
    });

    // aadb
    Route::group(['prefix' => 'aadb', 'as' => 'aadb.'], function () {
        Route::get('dashboard', [SuperUserController::class, 'Aadb']);
        Route::get('kendaraan/{aksi}/{id}', [SuperUserController::class, 'Vehicle']);
        Route::get('laporan/{aksi}/{id}', [SuperUserController::class, 'ReportAadb']);
        Route::get('rekapitulasi', [SuperUserController::class, 'RecapAadb']);
        Route::get('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionAadb']);
        Route::get('surat/{aksi}/{id}', [SuperUserController::class, 'LetterAadb']);
        Route::get('/select2-dashboard/{aksi}/{id}', [SuperUserController::class, 'Select2AadbDashboard']);
        Route::post('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionAadb']);
        Route::post('kendaraan/{aksi}/{id}', [SuperUserController::class, 'Vehicle']);
        Route::get('surat/{aksi}/{id}', [SuperUserController::class, 'LetterAadb']);

        Route::get('dashboard/{aksi}', [SuperUserController::class, 'SearchChartDataAadb']);
        Route::get('/grafik-laporan', [SuperUserController::class, 'SearchChartReportAadb']);
        Route::post('/select2/{aksi}', [SuperUserController::class, 'Select2Aadb']);
        Route::post('/select2-dashboard/{aksi}/{id}', [SuperUserController::class, 'Select2AadbDashboard']);
    });

    // atk
    Route::group(['prefix' => 'atk', 'as' => 'atk.'], function() {
        Route::get('dashboard', [SuperUserController::class, 'Atk']);
        Route::get('barang/{aksi}/{id}', [SuperUserController::class, 'OfficeStationery']);
        Route::get('usulan/{aksi}/{id}', [SuperUserController::class, 'SubmissionAtk']);
        Route::get('/select2/{aksi}/{id}', [SuperUserController::class, 'Select2Atk']);
        Route::get('/select2-dashboard/{aksi}/{id}', [SuperUserController::class, 'Select2AtkDashboard']);
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
        Route::get('js/{aksi}/{id}', [SuperUserController::class, 'JsGdn']);

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
    Route::get('verif/{aksi}/{id}', [UserController::class, 'Verification']);
    Route::get('surat/{aksi}/{id}', [UserController::class, 'Letter']);
    Route::get('cetak-surat/{modul}/{id}', [UserController::class, 'PrintLetter']);


    Route::post('profil/{aksi}/{id}', [UserController::class, 'Profile']);
    Route::post('verif/{aksi}/{id}', [UserController::class, 'Verification'])->middleware('2fa');

    // Oldat
    Route::group(['prefix' => 'oldat', 'as' => 'oldat'], function () {
        Route::get('dashboard', [UserController::class, 'Oldat']);
        Route::get('barang/{aksi}/{id}', [UserController::class, 'Items']);
        Route::get('/grafik', [UserController::class, 'SearchChartDataOldat']);
        Route::get('/select2-dashboard/{aksi}/{id}', [UserController::class, 'Select2OldatDashboard']);
        Route::get('usulan/{aksi}/{id}', [UserController::class, 'SubmissionOldat']);
        Route::get('/select2/{aksi}', [UserController::class, 'Select2Oldat']);

        Route::post('usulan/{aksi}/{id}', [UserController::class, 'SubmissionOldat']);
        Route::post('barang/{aksi}/{id}', [UserController::class, 'Items']);
        Route::post('/select2-dashboard/{aksi}/{id}', [UserController::class, 'Select2OldatDashboard']);

    });

    // Aadb
    Route::group(['prefix' => 'aadb', 'as' => 'aadb'], function () {
        Route::get('dashboard', [UserController::class, 'Aadb']);
        Route::get('/grafik', [UserController::class, 'SearchChartDataAadb']);
        Route::get('kendaraan/{aksi}/{id}', [UserController::class, 'Vehicle']);
        Route::get('usulan/{aksi}/{id}', [UserController::class, 'SubmissionAadb']);
        Route::get('/select2/{aksi}', [SuperUserController::class, 'Select2Aadb']);
        Route::get('/select2-dashboard/{aksi}/{id}', [UserController::class, 'Select2AadbDashboard']);

        Route::post('kendaraan/{aksi}/{id}', [UserController::class, 'Vehicle']);
        Route::post('usulan/{aksi}/{id}', [UserController::class, 'SubmissionAadb']);
        Route::post('/select2/{aksi}', [UserController::class, 'Select2Aadb']);
        Route::post('/select2-dashboard/{aksi}/{id}', [UserController::class, 'Select2AadbDashboard']);

    });

    // Gedung dan Bangunan
    Route::group(['prefix' => 'gdn', 'as' => 'gdn'], function () {
        Route::get('dashboard', [UserController::class, 'Gdn']);
        Route::get('usulan/{aksi}/{id}', [UserController::class, 'SubmissionGdn']);
        Route::get('js/{aksi}/{id}', [UserController::class, 'JsGdn']);

        Route::post('usulan/{aksi}/{id}', [UserController::class, 'SubmissionGdn']);
    });

    // Rumah Dinas Negara
    Route::group(['prefix' => 'rdn', 'as' => 'rdn'], function () {
        Route::get('rumah/{aksi}/{id}', [UserController::class, 'OfficialResidence']);
    });

    // ATK
    Route::group(['prefix' => 'atk', 'as' => 'atk.'], function() {
        Route::get('dashboard', [UserController::class, 'Atk']);
        Route::get('barang/{aksi}/{id}', [UserController::class, 'OfficeStationery']);
        Route::get('usulan/{aksi}/{id}', [UserController::class, 'SubmissionAtk']);
        Route::get('/select2/{aksi}/{id}', [UserController::class, 'Select2Atk']);
        Route::get('surat/{aksi}/{id}', [UserController::class, 'LetterAtk']);
        Route::get('/select2-dashboard/{aksi}/{id}', [UserController::class, 'Select2AtkDashboard']);

        Route::post('surat/{aksi}/{id}', [UserController::class, 'LetterAtk']);
        Route::post('usulan/{aksi}/{id}', [UserController::class, 'SubmissionAtk']);
        Route::post('/select2/{aksi}/{id}', [UserController::class, 'Select2Atk']);
        Route::post('/select2-dashboard/{aksi}/{id}', [UserController::class, 'Select2AtkDashboard']);

        Route::get('/grafik', [UserController::class, 'SearchChartDataAtk']);
    });

});

