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
    public function index(Request $request)
    {
        $dosenCount = DB::table('dosen')->count();
        $mahasiswaCount = DB::table('mahasiswa')->count();
        $matakuliahCount = DB::table('matakuliah')->count();
        $usersCount = DB::table('users')->count();
        $dosen = DB::table('dosen')->get();
        $search = $request->input('search');
        $query = DB::table('matakuliah');

        if ($search) {
            $query->where('nama_matakuliah', 'like', '%' . $search . '%');
        }

        $matakuliah = $query->paginate(5);

        return view('admin.home', compact('dosen', 'dosenCount', 'mahasiswaCount', 'matakuliahCount', 'usersCount', 'matakuliah', 'search'));
    }


    public function datadosen(Request $request, $nidn)
    {
    // Search
    $search = $request->input('search');
    $query = DB::table('mahasiswa')
        ->where('nidn', $nidn);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('nim', 'like', '%' . $search . '%');
        });
    }


    // Pagination
    $mahasiswa = $query->paginate(5);

    return view('admin.datadosen', compact('mahasiswa', 'search', 'nidn'));
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function datamahasiswa(Request $request)
    {
        // Get the nim value from the URL query parameters
        $nim = $request->input('nim');

        // Search
        $search = $request->input('search');

        $query = DB::table('transkrip_mahasiswa')
            ->leftJoin('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
            ->select('transkrip_mahasiswa.*', 'matakuliah.nama_matakuliah');

        if ($search) {
            $query->where('matakuliah.nama_matakuliah', 'like', '%' . $search . '%')
                  ->where('transkrip_mahasiswa.nim', $nim);
        } else {
            $query->where('transkrip_mahasiswa.nim', $nim);
        }

        // Pagination
        $transkrip_mahasiswa = $query->paginate(5);
        $matakuliah = DB::table('matakuliah')->get();


        return view('admin.adminmahasiswa', compact('transkrip_mahasiswa', 'search', 'nim', 'matakuliah'));
    }


}
