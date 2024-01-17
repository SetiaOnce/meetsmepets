<?php

use App\Http\Controllers\Auth\LoginAdminController;
use App\Http\Controllers\Auth\LoginPegawaiController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\SelectController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::controller(FrontendController::class)->group(function(){
    Route::get('/', 'index');
    Route::get('/ajax/load_login_info', 'loginInfo');
});
/*For handle login admin*/ 
Route::controller(LoginAdminController::class)->group(function(){
    Route::get('/login-admin', 'index');
    Route::post('/ajax/request_login_admin', 'requestLogin');
    Route::get('/logout', 'logout');
});
/*For handle login pegawai*/ 
Route::controller(LoginPegawaiController::class)->group(function(){
    Route::get('/login', 'index');
    Route::post('/ajax/request_login_pegawai', 'requestLogin');
    Route::get('/logout-pegawai', 'logout');
});
Route::get('/reloadcaptcha', function () {
	return captcha_img();
});
/*Handle All Front Ajax*/
Route::group(['prefix' => 'front'], function () {
    Route::controller(FrontendController::class)->group(function(){
        Route::get('/banner/view', 'BannerView');
        Route::get('/aplikasi/view', 'AplikasiView');
        Route::get('/layanan/view', 'LayananView');
    });
});
/*Handle select all master data*/
Route::group(['prefix' => 'select'], function () {
    Route::controller(SelectController::class)->group(function(){
        Route::get('/get_pegawai', 'getPegawai');
        Route::get('/get_aplikasi', 'getAplikasi');
        Route::get('/get_level_aplikasi', 'getLevelAplikasi');
    });
});

// App Admin
Route::group(['prefix' => 'app_admin'], function () {
    require base_path('routes/common.php');
    Route::group(['middleware' => 'auth'], function() {
        Route::group(['middleware' => 'checkRole:1'], function() {
            require base_path('routes/admin.php');
        });
    });
});
// handle pegawai route
Route::group(['prefix' => 'app_pegawai'], function () {
    require base_path('routes/pegawai.php');
});