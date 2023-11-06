<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;
class MahasiswaController extends Controller
{
    public function index(Request $request)
{
    // Get the authenticated user's idn
    $idn = Auth::user()->idn;

    // Search
    $search = $request->input('search');

    $query = DB::table('transkrip_mahasiswa')
        ->leftJoin('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->select('transkrip_mahasiswa.*', 'matakuliah.nama_matakuliah')
        ->where('transkrip_mahasiswa.nim', $idn); // Filter by authenticated user's idn

    if ($search) {
        $query->where('matakuliah.nama_matakuliah', 'like', '%' . $search . '%');
    }

    // Pagination
    $transkrip_mahasiswa = $query->paginate(5);
    $matakuliah = DB::table('matakuliah')->get();

    return view('mahasiswa.home', compact('mahasiswaData', 'transkrip_mahasiswa', 'search', 'matakuliah'));
}


}
