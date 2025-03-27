<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/selamat', function () {
    return view('selamat',
                 [
                    'nama'=>'Aurora Shintya Pasya',
                    'nim'=>'607032330095'
                 ]
                );
});

// route ke utama
Route::get('/utama', function () {
    return view('layout',
                 [
                    'nama'=>'Aurora Shintya Pasya',
                    'title'=>'Selamat Datang di Matakuliah Web Framework'
                 ]
                );
});
//contoh routes yang diakses via controller contoh1 dan method show
Route::get('/contoh1', [App\Http\Controllers\Contoh1::class, 'show']);
Route::get('/Contoh2Controller', [App\Http\Controllers\Contoh2Controller::class, 'show']);  
Route::get('/coa', [App\Http\Controllers\CoaController::class, 'index']);