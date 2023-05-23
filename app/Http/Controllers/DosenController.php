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
}
