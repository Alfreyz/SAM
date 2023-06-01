<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;
use Illuminate\Pagination\Paginator;


class AdminController extends Controller
{
    public function index()
    {
        $dosen = DB::table('dosen')->get();
        return view('admin.home',compact('dosen'));
    }

    public function datadosen(){
        $mahasiswa = DB::table('mahasiswa')->paginate(5);
        return view('admin.datadosen', compact('mahasiswa'));
    }

    public function datamahasiswa(){
        $mahasiswa = DB::table('users')->where('role', 'mahasiswa')->get();
        return view('admin.datamahasiswa', compact('mahasiswa'));
    }
}
