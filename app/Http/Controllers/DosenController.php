<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;
class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('mahasiswa');

        $search = $request->input('search');

        if (!empty($search)) {
            $query->where(function ($mahasiswa) use ($search) {
                $mahasiswa->where('nim', 'like', '%' . $search . '%');
            });
        }

        $mahasiswa = $query->paginate(5);

        return view('dosen.home', compact('mahasiswa', 'search'));
    }

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
