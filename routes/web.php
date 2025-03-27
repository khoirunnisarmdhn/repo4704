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

Route::get('/', function () {
    return view('welcome');
});

// untuk simulasi penggunaan route dengan view mengarah ke selamat.blade.php
// kemudian mengirimkan dua variabel ke view yaitu nama dengan isi Putri Valina dan nim dengan isi 113030044
Route::get('/selamat', function () {
    return view('selamat',
                 [
                    'nama'=>'Putri Valina',
                    'nim'=>'113030044'
                 ]
                );
});

// route ke utama
Route::get('/utama', function () {
    return view('layout',
                 [
                    'nama'=>'Putri Valina',
                    'title'=>'Selamat Datang di Matakuliah Web Framework'
                 ]
                );
});

// contoh route dengan mengakses method show di class contoh1controller
Route::get('/contoh1', [App\Http\Controllers\Contoh1Controller::class, 'show']);

Route::get('/contoh2', [App\Http\Controllers\Contoh2Controller::class, 'show']);
Route::get('/coa', [App\Http\Controllers\CoaController::class, 'index']);

