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

    public function displayTable()
    {
        $bkCodes = DB::table('bk_cpl')->distinct()->pluck('kode_bk');
        $cplCodes = DB::table('bk_cpl')->distinct()->pluck('kode_cpl');
        $bkall = DB::table('bk')->paginate(6, ['*'], 'page_bk');
        $cplall = DB::table('cpl')->paginate(5, ['*'], 'page_cpl');
        // Fetch data for each CPL and BK combination
        $cplData = [];
        foreach ($cplCodes as $cplCode) {
            foreach ($bkCodes as $bkCode) {
                $count = DB::table('bk_cpl')
                    ->where('kode_cpl', $cplCode)
                    ->where('kode_bk', $bkCode)
                    ->count();

                $cplData[$cplCode][$bkCode] = $count;
            }
        }
        return view('hubungan_bk_cpl', compact('bkCodes', 'cplData', 'cplCodes', 'bkall', 'cplall'));
    }


    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('login');
    }
}
