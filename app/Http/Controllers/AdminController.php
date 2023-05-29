<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }

    public function datadosen(){
        $dosen = DB::table('users')->where('role', 'dosen')->get();
        return view('admin.datadosen', ['dosen' => $dosen]);
    }

    public function datamahasiswa(){
        $mahasiswa = DB::table('users')->where('role', 'mahasiswa')->get();
        return view('admin.datamahasiswa', ['mahasiswa' => $mahasiswa]);
    }
}
