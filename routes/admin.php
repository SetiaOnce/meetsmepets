<?php

use App\Http\Controllers\Backend\WebsitesInfomationController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserProfilesController;
use App\Http\Controllers\Backend\UsersSystemController;
use Illuminate\Support\Facades\Route;

Route::get('/app_admin', [DashboardController::class, 'index']);
Route::get('/site_information', [WebsitesInfomationController::class, 'index']);
// App Admin
Route::group(['prefix' => 'app_admin'], function () {
    Route::get('/manage_users', [UsersSystemController::class,'index'])->name('manage_users');
    Route::get('/{username}', [UserProfilesController::class,'index'])->name('user_profile');
});
//Api Ajax App
Route::group(['prefix' => 'api'], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('first_widget', 'firstWidget')->name('first_widget');
        });
    });
    Route::controller(WebsitesInfomationController::class)->group(function () {
        Route::get('load_website_information', 'data');
        Route::post('website_information_update', 'update');
    });
    Route::controller(UserProfilesController::class)->group(function () {
        Route::post('update_userprofile', 'update_userprofile');
        Route::post('update_userpassprofil', 'update_userpassprofil');
    });
    //Manage Users
    Route::get('/manage_users/show', [UsersSystemController::class, 'show'])->name('show_users');
    Route::post('/manage_users/store', [UsersSystemController::class, 'store'])->name('store_users');
    Route::post('/manage_users/update', [UsersSystemController::class, 'update'])->name('update_users');
    Route::post('/manage_users/update_statususers', [UsersSystemController::class, 'update_statususers'])->name('update_statususers');
    Route::post('/manage_users/reset_userpass', [UsersSystemController::class, 'reset_userpass'])->name('reset_userpass');
});