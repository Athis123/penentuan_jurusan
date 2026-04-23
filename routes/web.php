<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Personil\UserController;

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

// Login Form
Route::get('/', [LoginController::class, 'index'])->name('login');

// Auth route
Route::prefix('auth')->as('auth.')->group(function () {
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Administrator
Route::group(['middleware' => ['auth'],'prefix' => 'administrator', 'as' => 'admin.'], function(){

    // Dashboard
    Route::group(['namespace' => 'App\Http\Controllers\Dashboard','prefix' => 'dashboard', 'as' => 'dashboard.'], function(){
        Route::get('/','DashboardController@index')->name('index');
        Route::get('/tasks/priority-data','DashboardController@getTasksByPriority')->name('priority');
    });

        // Data
    Route::group(['namespace' => 'App\Http\Controllers\Data','prefix' => 'data', 'as' => 'data.'], function(){
        
        // Kriteria
        Route::get('kriteria', 'KriteriaController@index')->name('kriteria.index');
        Route::get('kriteria/create', 'KriteriaController@create')->name('kriteria.create');
        Route::post('kriteria', 'KriteriaController@store')->name('kriteria.store');
        Route::get('kriteria/{id}/edit', 'KriteriaController@edit')->name('kriteria.edit');
        Route::put('kriteria/{id}', 'KriteriaController@update')->name('kriteria.update');
        Route::delete('/kriteria/{id}', 'KriteriaController@destroy')->name('kriteria.destroy');

        // Data Siswa
        Route::get('siswa', 'DataSiswaController@index')->name('siswa.index');
        Route::get('siswa/create', 'DataSiswaController@create')->name('siswa.create');
        Route::post('siswa', 'DataSiswaController@store')->name('siswa.store');
        Route::get('siswa/{id}/edit', 'DataSiswaController@edit')->name('siswa.edit');
        Route::put('siswa/{id}', 'DataSiswaController@update')->name('siswa.update');
        Route::delete('/siswa/{id}', 'DataSiswaController@destroy')->name('siswa.destroy');

        // Penilaian
        Route::get('penilaian', 'NilaiController@index')->name('penilaian.index');
        Route::post('penilaian/bulkstore', 'NilaiController@bulkStore')->name('penilaian.bulkstore');
        Route::delete('/penilaian/{id}', 'NilaiController@destroy')->name('penilaian.destroy');

        // Perhitungan
        Route::get('perhitungan', 'PerhitunganController@index')->name('perhitungan.index');

        // hasil perhitungan
        Route::get('hasil_perhitungan', 'HasilPerhitunganController@index')->name('perhitungan.result');

        // exportpdf
        Route::get('exportpdf', 'HasilPerhitunganController@exportPdf')->name('perhitungan.pdf');
    });

        // Data Master
    Route::group(['middleware' => ['role:admin|operator'],'namespace' => 'App\Http\Controllers\Master','prefix' => 'master', 'as' => 'master.'], function(){

    });

   // Personil (PEGAWAI)
    Route::group(['middleware' => ['role:admin|operator'],'namespace' => 'App\Http\Controllers\Personil','prefix' => 'personil', 'as' => 'personil.'], function(){
        // Profil
        Route::get('profil','ProfileController@index')->name('profil.index');
        Route::get('profil/form','ProfileController@form')->name('profil.form');
        Route::put('profil/update','ProfileController@update')->name('profil.update');

        // User
        Route::resource('user',UserController::class);
    });
});
require __DIR__.'/auth.php';
