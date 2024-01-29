<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\SelectController;
use App\Http\Controllers\Welcome\LoginController;
use App\Http\Controllers\Welcome\RegisterController;
use App\Http\Controllers\Welcome\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function(){
    Route::get('/login-admin', 'index');
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/logout', 'logout_sessions')->name('logout_sessions');
    });
});
//Api ajax App Auth
Route::group(['prefix' => 'api'], function () {
    Route::controller(CommonController::class)->group(function(){
        Route::get('/site_info', 'siteInfo');
        Route::get('/user_info', 'userInfo');
        Route::get('/owner_info', 'ownerInfo');
    });
    Route::controller(SelectController::class)->group(function(){
        Route::group(['prefix' => 'select'], function () {
            Route::get('/looking_for', 'lookingFor');
            Route::get('/interest', 'interest');
        });
    });
    Route::controller(AuthController::class)->group(function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('/first_login', 'first_login')->name('first_login');
            Route::post('/second_login', 'second_login')->name('second_login');
        });
    });
});
// All Welcome Request
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::controller(LoginController::class)->group(function(){
    Route::get('/auth', 'index');
    Route::get('/logout', 'logout_sessions');
    Route::group(['prefix' => 'api'], function () {
        Route::post('auth/first_step', 'first_step')->name('first_step');
        Route::post('auth/second_step', 'second_step')->name('second_step');
    });
});
Route::controller(RegisterController::class)->group(function(){
    Route::get('/register', 'index');
    Route::group(['prefix' => 'api'], function () {
        Route::post('register/first_register', 'first_step')->name('first_register');
        Route::post('register/second_tegister', 'second_step')->name('second_tegister');
    });
});
Route::group(['middleware' => 'AuthOwner'], function() {
    require base_path('routes/owner.php');
});
Route::group(['middleware' => 'auth'], function() {
    require base_path('routes/admin.php');
});