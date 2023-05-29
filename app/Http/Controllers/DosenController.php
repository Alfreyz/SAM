<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;
class DosenController extends Controller
{
    public function index()
    {
        return view('dosen.home');
    }

    public function datamahasiswa(){
        $mahasiswa = DB::table('users')->where('role', 'mahasiswa')->get();
        return view('dosen.datamahasiswa', ['mahasiswa' => $mahasiswa]);
    }
}
