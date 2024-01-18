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

Route::get('/error/{nim?}', function ($nim = null) {
    return view('error.custome')->with('nim', $nim);
})->name('error.route');


Route::get('/', function () {
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
    Route::get('/hubungan_bk_cpl', 'HomeController@displayTable')->name('hubungan_bk_cpl');
    Route::post('/reset-relasi', 'HomeController@resetRelasi')->name('reset.relasi');
    Route::post('/upload-bk', 'HomeController@uploadBK')->name('upload.bk');
    Route::post('/upload-cpl', 'HomeController@uploadCPL')->name('upload.cpl');
    Route::post('/upload-bk-cpl', 'HomeController@uploadBK_CPL')->name('upload.bk_cpl');
    Route::delete('/delete-bk/{id}', 'HomeController@deleteBK')->name('delete.bk');
    Route::delete('/delete-cpl/{id}', 'HomeController@deleteCPL')->name('delete.cpl');


    Route::middleware(['admin'])->group(function () {
        Route::get('admin', 'AdminController@index')->name('admin.home');
        Route::get('datadosen/{nidn}/{selectedAngkatan?}', 'AdminController@datadosen')->name('admin.datadosen');
        Route::get('adminmahasiswa', 'AdminController@datamahasiswa')->name('admin.adminmahasiswa');
        Route::post('admin/upload_filem', 'AdminController@uploadfilem')->name('admin.uploadfilem');
        Route::post('admin/upload_filetm/{nim}', 'AdminController@uploadfiletm')->name('admin.uploadfiletm');
        Route::post('admin/updatemahasiswa', 'AdminController@updatemahasiswa')->name('admin.updatemahasiswa');
        Route::post('admin/updatenamadosen', 'AdminController@updatenamadosen')->name('admin.updatenamadosen');
        Route::post('admin/updatenilai/{nim}', 'AdminController@updatenilai')->name('admin.updatenilai');
        Route::post('admin/adddosen', 'AdminController@addDosen')->name('admin.adddosen');
        Route::post('admin/uploadmatakuliah', 'AdminController@uploadMatakuliah')->name('admin.uploadmatakuliah');
        Route::delete('admin/deletematakuliah/{kode_matakuliah}', 'AdminController@deleteMatakuliah')->name('admin.deletematakuliah');
    });

    Route::middleware(['dosen'])->group(function () {
        Route::get('dosen/{selectedAngkatan?}', 'DosenController@index')->name('dosen.home');
        Route::get('datamahasiswa', 'DosenController@datamahasiswa')->name('dosen.datamahasiswa');
        Route::post('dosen/updatenilai/{nim}', 'DosenController@updatenilai')->name('dosen.updatenilai');
    });

    Route::middleware(['mahasiswa'])->group(function(){
        Route::get('mahasiswa', 'MahasiswaController@index')->name('mahasiswa.home');
    });
});
