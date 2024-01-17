<?php

use App\Http\Controllers\Backend\Admin\AksesSsoController;
use App\Http\Controllers\Backend\Admin\AplikasiController;
use App\Http\Controllers\Backend\Admin\DashboardController;
use App\Http\Controllers\Backend\Admin\DataSdmController;
use App\Http\Controllers\Backend\Admin\HeadBannerController;
use App\Http\Controllers\Backend\Admin\InformasiWebsiteController;
use App\Http\Controllers\Backend\Admin\LayananController;
use App\Http\Controllers\Backend\Admin\LevelAplikasiController;
use App\Http\Controllers\Backend\Admin\PengelolaPortalController;
use App\Http\Controllers\Backend\Admin\SikDataKantorController;
use App\Http\Controllers\Backend\Admin\SikPegawaiHonorerController;
use App\Http\Controllers\Backend\Admin\SikPegawaiPerKantorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)->group(function(){
    Route::get('/dashboard', 'index');
    Route::get('/dashboard/user_profile', 'UserProfile');
});
/*all manage content*/ 
Route::group(['prefix' => 'content'], function () {
    Route::controller(InformasiWebsiteController::class)->group(function(){
        Route::get('/website_information', 'index');
        Route::get('/ajax/load_website_information', 'data');
        Route::post('/ajax/website_information_update', 'update');
        Route::post('/ajax/tautan_save', 'tautanSave');
        Route::post('/ajax/tautan_load', 'tautanLoad');
        Route::post('/ajax/tautan_destroy', 'tautanDestroy');
    });
    Route::controller(HeadBannerController::class)->group(function(){
        Route::get('/head_banner', 'index');
        Route::get('/ajax/load_head_banner', 'data');
        Route::post('/ajax/head_banner_update', 'update');
    });
    Route::controller(LayananController::class)->group(function(){
        Route::get('/layanan', 'index');
        Route::post('/ajax/load_layanan', 'data');
        Route::post('/ajax/layanan_save', 'store');
        Route::get('/ajax/layanan_edit', 'edit');
        Route::post('/ajax/layanan_update', 'update');
        Route::post('/ajax/layanan_update_status', 'updateStatus');
        Route::post('/ajax/layanan_destroy', 'destroy');
    });
});
/*hadle manage data sdm*/ 
Route::controller(DataSdmController::class)->group(function(){
    Route::get('/data_sdm', 'index');
    Route::post('/ajax/load_data_sdm', 'data');
    Route::get('/ajax/load_detail_sdm', 'detailSdm');
    Route::post('/ajax/data_sdm_update_status', 'updateStatus');
    Route::post('/ajax/data_sdm_reset_password', 'resetPassword');
});
/*hadle manage aplikasi*/ 
Route::controller(AplikasiController::class)->group(function(){
    Route::get('/aplikasi', 'index');
    Route::post('/ajax/load_aplikasi', 'data');
    Route::post('/ajax/aplikasi_save', 'store');
    Route::get('/ajax/aplikasi_edit', 'edit');
    Route::post('/ajax/aplikasi_update', 'update');
    Route::post('/ajax/aplikasi_update_status', 'updateStatus');
    Route::post('/ajax/aplikasi_destroy', 'destroy');
});
/*hadle manage level aplikasi*/ 
Route::controller(LevelAplikasiController::class)->group(function(){
    Route::get('/level_aplikasi', 'index');
    Route::post('/ajax/load_level_aplikasi', 'data');
    Route::post('/ajax/level_aplikasi_save', 'store');
    Route::get('/ajax/level_aplikasi_edit', 'edit');
    Route::post('/ajax/level_aplikasi_update', 'update');
    Route::post('/ajax/level_aplikasi_update_status', 'updateStatus');
    Route::post('/ajax/level_aplikasi_destroy', 'destroy');
});
/*hadle manage pengelola portal*/ 
Route::controller(PengelolaPortalController::class)->group(function(){
    Route::get('/pengelola_portal', 'index');
    Route::post('/ajax/load_pengelola_portal', 'data');
    Route::post('/ajax/pengelola_portal_save', 'store');
    Route::post('/ajax/pengelola_portal_destroy', 'destroy');
});
/*hadle manage akses sso*/ 
Route::controller(AksesSsoController::class)->group(function(){
    Route::get('/akses_sso', 'index');
    Route::post('/ajax/load_akses_sso', 'data');
    Route::post('/ajax/akses_sso_save', 'store');
    Route::get('/ajax/akses_sso_edit', 'edit');
    Route::post('/ajax/akses_sso_update', 'update');
    Route::post('/ajax/akses_sso_update_status', 'updateStatus');
    Route::post('/ajax/akses_sso_destroy', 'destroy');
});
/*manage data sik*/ 
Route::group(['prefix' => 'sik'], function () {
    Route::controller(SikDataKantorController::class)->group(function(){
        Route::get('/data_kantor', 'index');
        Route::post('/ajax/load_data_kantor', 'data');
    });
    Route::controller(SikPegawaiPerKantorController::class)->group(function(){
        Route::get('/pegawai_perkantor', 'index');
        Route::post('/ajax/load_pegawai_perkantor', 'data');
        Route::post('/ajax/sincronize_sik_pegawai_perkantor', 'sincronize');
    });
    Route::controller(SikPegawaiHonorerController::class)->group(function(){
        Route::get('/pegawai_honorer', 'index');
        Route::post('/ajax/load_pegawai_honorer', 'data');
        Route::post('/ajax/sincronize_sik_pegawai_honorer', 'sincronize');
    });
});