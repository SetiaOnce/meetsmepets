<?php

use App\Http\Controllers\Backend\Pegawai\DashboardController;
use App\Http\Controllers\Backend\Pegawai\PegawaiProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)->group(function(){
    Route::get('/dashboard', 'index');
    Route::get('/dashboard/aplication', 'application');
    Route::get('/dashboard/load_statistik_login', 'statistikLogin');
});
// pegawai profile
Route::controller(PegawaiProfileController::class)->group(function(){
    Route::get('/myprofile', 'index');
    Route::get('/ajax/pegawai/show', 'pegawaiInfo');
    Route::post('/ajax/pegawai/reset_password', 'resetPassword');
});