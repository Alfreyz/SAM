<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;
class MahasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.home');
    }
}
