<?php

use App\Http\Controllers\Backend\WebsitesInfomationController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\MasterCategoryController;
use App\Http\Controllers\Backend\MasterInterestController;
use App\Http\Controllers\Backend\UserProfilesController;
use App\Http\Controllers\Backend\UsersPublicController;
use App\Http\Controllers\Backend\UsersSystemController;
use Illuminate\Support\Facades\Route;

Route::get('/app_admin', [DashboardController::class, 'index']);
Route::get('/site_information', [WebsitesInfomationController::class, 'index']);
// App Admin
Route::group(['prefix' => 'app_admin'], function () {
    Route::get('/manage_users', [UsersSystemController::class,'index'])->name('manage_users');
    Route::get('/users_public', [UsersPublicController::class,'index'])->name('users_public');
    Route::get('/{username}', [UserProfilesController::class,'index'])->name('user_profile');
});
// Mastering
Route::group(['prefix' => 'master'], function () {
    Route::get('/category', [MasterCategoryController::class,'index'])->name('master_category');
    Route::get('/interest', [MasterInterestController::class,'index'])->name('master_interest');
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
     //Manage Users Public
     Route::get('/manage_userspublic/show', [UsersPublicController::class, 'show'])->name('show_userspublic');
    Route::post('/manage_userspublic/update_status', [UsersPublicController::class, 'update_status'])->name('update_statususerspublic');

    // master category
    Route::controller(MasterCategoryController::class)->group(function () {
        Route::group(['prefix' => 'master_category'], function () {
            Route::get('show', 'show')->name('show_category');
            Route::post('store', 'store')->name('store_category');
            Route::post('update', 'update')->name('update_category');
            Route::post('update_status', 'update_status')->name('update_status_category');
        });
    });
    // master interest
    Route::controller(MasterInterestController::class)->group(function () {
        Route::group(['prefix' => 'master_interest'], function () {
            Route::get('show', 'show')->name('show_interest');
            Route::post('store', 'store')->name('store_interest');
            Route::post('update', 'update')->name('update_interest');
            Route::post('update_status', 'update_status')->name('update_status_interest');
        });
    });
});