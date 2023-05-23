<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;

Route::get('/debug-users', function () {
    $users = \App\User::all();
    dd($users);
});

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/logout', 'HomeController@logout')->name('logout');

    Route::middleware(['admin'])->group(function () {
        Route::get('admin', 'AdminController@index')->name('admin.home');
        Route::get('datadosen', 'AdminController@datadosen')->name('admin.datadosen');
        Route::get('A_datamahasiswa', 'AdminController@A_datamahasiswa')->name('A_datamahasiswa');
    });

    Route::middleware(['dosen'])->group(function () {
        Route::get('dosen', 'DosenController@index')->name('dosen.home');
        Route::get('D_datamahasiswa', 'DosenController@D_datamahasiswa')->name('D_datamahasiswa');
    });

    Route::middleware(['mahasiswa'])->group(function(){
        Route::get('/mahasiswa', 'MahasiswaController@index')->name('mahasiswa.home');
    });
});
