<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;
use Illuminate\Support\Facades\Log;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $role = Auth::user()->role;
        if ($role == "admin") {
            return redirect()->route('admin.home');
        } else if ($role == "dosen") {
            return redirect()->route('dosen.home');
        } else if ($role == "mahasiswa") {
            return redirect()->route('mahasiswa.home');
        } else {
            return redirect()->route('logout');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('login');
    }
}
