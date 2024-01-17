<?php

use App\Http\Controllers\Backend\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(CommonController::class)->group(function(){
    Route::get('/ajax/load_site_info', 'siteInfo');
    Route::get('/ajax/user_info', 'userInfo');
    Route::get('/ajax/sincronize_sik', 'sincronizeSik');
});