<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Frontend\ExploreController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LoveController;
use App\Http\Controllers\Frontend\ProfileOwnerController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index']);
Route::get('/love', [LoveController::class, 'index']);
Route::get('/explore', [ExploreController::class, 'index']);
Route::get('/viewpets/{id}', [ExploreController::class, 'viewPets']);
Route::get('/chat', [ChatController::class, 'index']);
Route::get('/chat/{id}', [ChatController::class, 'startChat']);
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
        Route::get('/update_time_online', 'updateOnline');
        Route::post('/like_owner', 'statusLike');
        Route::post('/like_pets', 'statusPets');
    });
    // home ajax app
    Route::controller(HomeController::class)->group(function () {
        Route::get('/near_owner', 'nearOwner');
    });
    // love
    Route::controller(LoveController::class)->group(function () {
        Route::get('/load_data_filter', 'dataFilter');
        Route::post('/save_filter_data', 'saveFilter');
        Route::post('/pets_populer', 'petsPupuler');
        Route::post('/pets_allcontent', 'petsAll');
    });
    // explore
    Route::controller(ExploreController::class)->group(function () {
        Route::get('/explore_pets', 'data');
    });
    // profile ajax app
    Route::controller(ProfileOwnerController::class)->group(function () {
        Route::group(['prefix' => 'profile'], function () {        
            // profile setting
            Route::post('/update_subscribe', 'updateSubscribe');
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
    // chat ajax app
    Route::controller(ChatController::class)->group(function () {
        Route::get('/chat_owner_love', 'ChatLove');
        Route::get('/all_message', 'allMessage');
        Route::get('/personal_chat', 'personalChat');
        Route::post('/send_message', 'sendMessage');
    });
});