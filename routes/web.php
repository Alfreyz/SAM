<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/debug-users', function () {
    $users = \App\User::all();
    dd($users);
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::post('/register', [RegisterController::class, 'create']);


Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/logout', 'HomeController@logout')->name('logout');

    Route::middleware(['admin'])->group(function () {
        Route::get('admin', 'AdminController@index')->name('admin.home');
        Route::get('datadosen/{nidn}', 'AdminController@datadosen')->name('admin.datadosen');
        Route::get('adminmahasiswa', 'AdminController@datamahasiswa')->name('admin.adminmahasiswa');
    });

    Route::middleware(['dosen'])->group(function () {
        Route::get('dosen', 'DosenController@index')->name('dosen.home');
        Route::get('datamahasiswa', 'DosenController@datamahasiswa')->name('dosen.datamahasiswa');
    });

    Route::middleware(['mahasiswa'])->group(function(){
        Route::get('mahasiswa', 'MahasiswaController@index')->name('mahasiswa.home');
    });
});
