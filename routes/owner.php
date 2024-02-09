<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProfileOwnerController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index']);
Route::controller(ProfileOwnerController::class)->group(function () {
    Route::group(['prefix' => 'profile'], function () {        
        Route::get('', 'index');
        Route::get('/setting', 'settingPage');
        Route::get('/edit', 'editPage');
    });
});
//Api Ajax App
Route::group(['prefix' => 'api'], function () {
    // common ajax app
    Route::controller(CommonController::class)->group(function () {
        Route::post('/like_owner', 'statusLike');
    });
    Route::controller(HomeController::class)->group(function () {
        Route::get('/near_owner', 'nearOwner');
    });
    Route::controller(ProfileOwnerController::class)->group(function () {
        Route::group(['prefix' => 'profile'], function () {        
            // profile setting
            Route::post('/save_username', 'saveUsername');
            Route::post('/save_phonenumber', 'savePhone');
            Route::post('/save_email', 'saveEmail');
            Route::post('/save_location', 'saveLocation');
            Route::post('/save_gender', 'saveGender');
            Route::post('/save_distance', 'saveDistance');
            Route::post('/save_lookingfor', 'saveLookingFor');
            // profile edit
            // Route::post('/load_petsalbum', 'loadPets');
            Route::post('/save_fullname', 'saveFullName');
            Route::post('/save_fotoprofiles', 'saveFotoProfiles');
            // Route::post('/save_petsimages', 'savePetsImgae');
            Route::post('/save_interest', 'saveInterest');
            
            Route::post('/load_pets', 'loadPets');
            Route::post('/save_pets', 'savePets');
            Route::post('/edit_pet', 'editPets');
            Route::post('/update_pets', 'updatePets');
            Route::post('/delete_pet', 'deletePets');
        });
    });
});