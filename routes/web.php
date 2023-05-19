<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return redirect()->route('login');
// });

// Route::get('/A_beranda', function () {
//     return redirect()->route('A_beranda');
// });
Auth::routes();

Route::get('/', 'HomeController@index')->name('Login');
Route::get('/A_beranda', function(){
    return view('Users.Admin.A_beranda');
});
Route::get('A_datadosen', function(){
    return view('Users.Admin.A_datadosen');
});
Route::get('A_datamahasiswa', function(){
    return view('Users.Admin.A_datamahasiswa');
});
Route::get('D_beranda', function(){
    return view('Users.Dosen.D_beranda');
});
Route::get('M_beranda', function(){
    return view('Users.Mahasiswa.M_beranda');
});
