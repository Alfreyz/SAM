<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $dosen = DB::table('dosen')->paginate(5, ['*'], 'page_dosen');
        $user = DB::table('users')->where('role', 'dosen')->get();
        $search = $request->input('search');
        $query = DB::table('matakuliah');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('nama_matakuliah', 'like', '%' . $search . '%')
                    ->orWhere('semester', 'like', '%' . $search . '%')
                    ->orWhere('bahan_kajian', 'like', '%' . $search . '%')
                    ->orWhere('cpl', 'like', '%' . $search . '%')
                    ->orWhere('kode_matakuliah', 'like', '%' . $search . '%');
            });
        }
        $matakuliah = $query->paginate(5, ['*'], 'page_matakuliah');
        return view('admin.home', compact('dosen', 'matakuliah', 'search', 'user'));
    }

    private function calculateAverages($data)
    {
        $averages = [];
        foreach ($data as $item => $values) {
            $average = count($values) > 0 ? (array_sum($values) / count($values)) : 0;
            $averages[$item] = number_format($average, 2);
        }
        ksort($averages);
        return $averages;
    }

    public function datadosen(Request $request, $nidn, $selectedAngkatan = null)
{
    $dosen = DB::table('dosen')->where('nidn', $nidn)->first();
    $search = $request->input('search');

    $angkatanList = DB::table('mahasiswa')->where('nidn', $nidn)->distinct()->pluck('angkatan');
    // $selectedAngkatan = $request->input('selectedAngkatan');
    $mahasiswaTableQuery = DB::table('mahasiswa')->where('nidn', $nidn);

    if ($search) {
        $mahasiswaTableQuery->where('nim', 'like', '%' . $search . '%');
    }

    $mahasiswaTable = $mahasiswaTableQuery->paginate(5);

    $selectedAngkatan = $selectedAngkatan ?? $angkatanList->first(); // Set default if null

    $mahasiswabarQuery = DB::table('mahasiswa')
        ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
        ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn', 'matakuliah.bahan_kajian', 'mahasiswa.angkatan', 'matakuliah.cpl', DB::raw('MAX(transkrip_mahasiswa.bobot) as max_bobot'))
        ->where('mahasiswa.nidn', $nidn)
        ->where('mahasiswa.angkatan', $selectedAngkatan)
        ->where('mahasiswa.status', 'aktif');

    if ($search) {
        $mahasiswabarQuery = $mahasiswabarQuery->where(function ($query) use ($search) {
            $query->where('mahasiswa.nim', 'like', '%' . $search . '%')
                  ->orWhere('matakuliah.bahan_kajian', 'like', '%' . $search . '%');
        });
    }

    $mahasiswabarQuery = $mahasiswabarQuery
        ->groupBy('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn', 'mahasiswa.angkatan', 'matakuliah.kode_matakuliah', 'matakuliah.bahan_kajian', 'matakuliah.cpl')
        ->get();

        $allMahasiswaData = $mahasiswabarQuery->groupBy('id');
        $bahan_kajian_data = [];
        $cpl_data = [];

        foreach ($allMahasiswaData as $group) {
            foreach ($group as $data) {
                $bahan_kajian = explode(',', $data->bahan_kajian);
                $cpl = explode(',', $data->cpl);

                foreach ($bahan_kajian as $bahan) {
                    $bahan_kajian_data[$bahan][] = $data->max_bobot;
                }

                foreach ($cpl as $cpl) {
                    $cpl_data[$cpl][] = $data->max_bobot;
                }
            }
        }

        $averages_bk = $this->calculateAverages($bahan_kajian_data);
        $averages_cpl = $this->calculateAverages($cpl_data);

        $labels_bk = [];
        $data_bk = [];

        foreach ($averages_bk as $bahan => $average) {
            $labels_bk[] = $bahan;
            $data_bk[] = $average;
        }

        $labels_cpl = [];
        $data_cpl = [];

        foreach ($averages_cpl as $cpl => $average) {
            $labels_cpl[] = $cpl;
            $data_cpl[] = $average;
        }

        $chartData = [
            'labels_bk' => $labels_bk,
            'data_bk' => $data_bk,
            'labels_cpl' => $labels_cpl,
            'data_cpl' => $data_cpl,
        ];

        if($request->ajax())
        {
            return response()->json($chartData);
        }
        return view('admin.datadosen', compact('mahasiswaTable', 'selectedAngkatan', 'angkatanList', 'chartData', 'search', 'nidn', 'dosen', 'labels_cpl', 'data_cpl','labels_bk', 'data_bk'));
    }



    public function datamahasiswa(Request $request)
    {
        $nim = $request->input('nim');
        $search = $request->input('search');
        $nama = DB::table('mahasiswa')->where('nim', $nim)->value('nama');
        $query = DB::table('transkrip_mahasiswa')
        ->leftJoin('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->leftJoin('mahasiswa', 'transkrip_mahasiswa.nim', '=', 'mahasiswa.nim')
        ->select('transkrip_mahasiswa.*', 'mahasiswa.nidn', 'matakuliah.semester', 'matakuliah.nama_matakuliah', 'matakuliah.bahan_kajian', 'matakuliah.cpl')
        ->where('transkrip_mahasiswa.nim', $nim);

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('matakuliah.nama_matakuliah', 'like', '%' . $search . '%')
                    ->orWhere('matakuliah.bahan_kajian', 'like', '%' . $search . '%')
                    ->orWhere('matakuliah.cpl', 'like', '%' . $search . '%')
                    ->orWhere('matakuliah.kode_matakuliah', 'like', '%' . $search . '%');
            });
        }
        $alldata = $query->paginate(5, ['*'], 'page_mahasiswa');
        $dataMahasiswa = $alldata->groupBy('id');
        $bahan_kajian_data = [];
        $cpl_data = [];
        foreach ($dataMahasiswa as $group) {
            foreach ($group as $data) {
                $bahan_kajian = explode(',', $data->bahan_kajian);
                $cpl = explode(',', $data->cpl);

                foreach ($bahan_kajian as $bahan) {
                    $bahan_kajian_data[$bahan][] = $data->bobot;
                }

                foreach ($cpl as $cpl) {
                    $cpl_data[$cpl][] = $data->bobot;
                }
            }
        }
        $averages_bk = $this->calculateAverages($bahan_kajian_data);
        $averages_cpl = $this->calculateAverages($cpl_data);

        $labels_bk = [];
        $data_bk = [];
        foreach ($averages_bk as $bahan => $average) {
            $labels_bk[] = $bahan;
            $data_bk[] = $average;
        }

        $labels_cpl = [];
        $data_cpl = [];
        foreach ($averages_cpl as $cpl => $average) {
            $labels_cpl[] = $cpl;
            $data_cpl[] = $average;
        }
        $mahasiswabarQuery = DB::table('mahasiswa')
        ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
        ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn','mahasiswa.angkatan','matakuliah.bahan_kajian', 'matakuliah.cpl', 'transkrip_mahasiswa.bobot')
        ->where('mahasiswa.nim', $nim)
        ->first();

        if ($mahasiswabarQuery) {
        $selectedNidn = $mahasiswabarQuery->nidn;

        $resultDosen = $this->datadosen($request, $selectedNidn, $mahasiswabarQuery->angkatan);
        $bk_group_avg = $resultDosen->data_bk;
        $cpl_group_avg = $resultDosen->data_cpl;

        $alldata = $query->paginate(5);
        $nidn = $alldata->first()->nidn;

        $dataBK = \DB::table('transkrip_mahasiswa')
        ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->join('bk', function($join) {
            $join->whereRaw('FIND_IN_SET(bk.kode_bk, matakuliah.bahan_kajian) > 0');
        })
        ->where('transkrip_mahasiswa.nim', $nim)
        ->select('bk.kode_bk', \DB::raw('COUNT(*) as jumlah_entri'))
        ->groupBy('bk.kode_bk')
        ->get();

        $dataCPL = \DB::table('transkrip_mahasiswa')
        ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->join('cpl', function($join) {
            $join->whereRaw('FIND_IN_SET(cpl.kode_cpl, matakuliah.cpl) > 0');
        })
        ->where('transkrip_mahasiswa.nim', $nim)
        ->select('cpl.kode_cpl', \DB::raw('COUNT(*) as jumlah_entri'))
        ->groupBy('cpl.kode_cpl')
        ->get();

        $dataCountBKInMatakuliah = \DB::table('bk')
        ->select('bk.kode_bk', \DB::raw('COUNT(*) as jumlah_entri'))
        ->join('matakuliah', function ($join) {
            $join->on(\DB::raw('FIND_IN_SET(bk.kode_bk, matakuliah.bahan_kajian)'), '>', \DB::raw('0'));
        })
        ->groupBy('bk.kode_bk')
        ->paginate(5, ['*'], 'page_bk');

        $dataCountCPLInMatakuliah = \DB::table('cpl')
        ->select('cpl.kode_cpl', \DB::raw('COUNT(*) as jumlah_entri'))
        ->join('matakuliah', function ($join) {
            $join->on(\DB::raw('FIND_IN_SET(cpl.kode_cpl, matakuliah.cpl)'), '>', \DB::raw('0'));
        })
        ->groupBy('cpl.kode_cpl')
        ->paginate(5, ['*'], 'page_cpl');

        return view('admin.adminmahasiswa', compact('alldata','nidn','dataBK','dataCPL','dataCountBKInMatakuliah','dataCountCPLInMatakuliah','nama','labels_bk', 'data_bk', 'labels_cpl', 'data_cpl', 'bk_group_avg', 'cpl_group_avg', 'search', 'nim'));
        } else {
            return redirect()->route('error.route', ['nim' => $nim])->withErrors(['error' => 'Data not found']);
        }
    }

    // Upload FILE MAHASISWA
    public function uploadfilem(Request $request)
    {
        try {
            $request->validate([
                'fileUpload' => 'required|mimes:csv,txt|max:10240',
            ]);

            $file = $request->file('fileUpload');
            $csvData = array_map('str_getcsv', file($file->path()));
            $headers = array_map('trim', array_shift($csvData));
            $expectedHeaders = ['nim','nama','password','nidn', 'angkatan', 'status'];
            if ($headers !== $expectedHeaders) {
                throw new \Exception('Invalid CSV format. Please check the column headers.');
            }
            foreach ($csvData as $row) {
                list($nim, $nama, $password, $nidn, $angkatan, $status) = $row;
                DB::table('mahasiswa')->insert([
                    'nim' => $nim,
                    'nama' => $nama,
                    'password' => Hash::make($password),
                    'nidn' => $nidn,
                    'angkatan' => $angkatan,
                    'status' => $status,
                ]);
            DB::table('users')->insert([
                'idn' => $nim,
                'password' => Hash::make($password),
                'role' => 'mahasiswa',
            ]);
        }
            return redirect()->back()->with('success', 'CSV data uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function uploadfiletm(Request $request,$nim)
    {
        try {
            $request->validate([
                'fileUpload' => 'required|mimes:csv,txt|max:10240',
            ]);

            $file = $request->file('fileUpload');

            $csvData = array_map('str_getcsv', file($file->path()));
            $headers = array_map('trim', array_shift($csvData));
            $expectedHeaders = ['nim', 'kode_matakuliah', 'nilai', 'bobot'];
            if ($headers !== $expectedHeaders) {
                throw new \Exception('Invalid CSV format. Please check the column headers.');
            }
            DB::beginTransaction();
            try {
                foreach ($csvData as $row) {

                    list($csvNim, $kode_matakuliah, $nilai, $bobot) = $row;
                    // Check if a record with the same nim and kode_matakuliah already exists
                    $existingRecord = DB::table('transkrip_mahasiswa')
                        ->where('nim', $csvNim)
                        ->where('kode_matakuliah', $kode_matakuliah)
                        ->first();
                    // if ($existingRecord) {
                    //     throw new \Exception('Duplicate entry: nim and kode_matakuliah combination already exists.');
                    // }

                    if($existingRecord){
                        DB::table('transkrip_mahasiswa')
                        ->where('nim', $csvNim)
                        ->where('kode_matakuliah', $kode_matakuliah)
                        ->update([
                            'nilai' => $nilai,
                            'bobot' => $bobot,
                        ]);
                    } else {
                        DB::table('transkrip_mahasiswa')->insert([
                            'nim' => $csvNim,
                            'kode_matakuliah' => $kode_matakuliah,
                            'nilai' => $nilai,
                            'bobot' => $bobot,
                        ]);
                    }
                    // Insert a new record
                    // DB::table('transkrip_mahasiswa')->insert([
                    //     'nim' => $csvNim,
                    //     'kode_matakuliah' => $kode_matakuliah,
                    //     'nilai' => $nilai,
                    //     'bobot' => $bobot,
                    // ]);
                }
                DB::commit();

                $redirectUrl = route('admin.adminmahasiswa', ['nim' => $nim]);
                return redirect($redirectUrl)->with('success', 'File uploaded successfully!');
            } catch (\Exception $e) {
                DB::rollback();
                $redirectUrlWithError = route('admin.adminmahasiswa', ['nim' => $nim]);
                return redirect($redirectUrlWithError)->withErrors([$e->getMessage()]);
            }

        } catch (\Exception $e) {
            return redirect()->route('admin.adminmahasiswa')->withErrors([$e->getMessage()]);
        }
    }


        public function updatemahasiswa(Request $request)
        {
        $request->validate([
            'nim' => 'required',
            'nama' => 'required|string',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        $nim = $request->input('nim');
        $nama = $request->input('nama');
        $status = $request->input('status');
        DB::table('mahasiswa')->where('nim', $nim)->update(['nama' => $nama, 'status' => $status]);
        return back()->with('success', 'Data mahasiswa berhasil diperbarui');
        }

        public function updatenilai(Request $request, $nim)
        {
            $request->validate([
                'kode_matakuliah' => 'required',
                'nilai' => 'required|in:A,A-,B+,B,B-,C+,C,D,E',
            ]);

            $kode_matakuliah = $request->input('kode_matakuliah');
            $nilai = $request->input('nilai');
            $bobot_baru = $this->calculateBobot($nilai);

            if ($nim) {
                // Ambil nilai bobot sebelum update
                $bobot_sebelum_update = DB::table('transkrip_mahasiswa')
                    ->where('nim', $nim)
                    ->where('kode_matakuliah', $kode_matakuliah)
                    ->value('bobot');

                // Update bobot baru
                DB::table('transkrip_mahasiswa')
                    ->where('nim', $nim)
                    ->where('kode_matakuliah', $kode_matakuliah)
                    ->update(['nilai' => $nilai, 'bobot' => $bobot_baru]);

                // Simpan bobot baru ke dalam sesi untuk data ini
                session(['bobot_baru_' . $kode_matakuliah => $bobot_baru, 'bobot_lama_' . $kode_matakuliah => $bobot_sebelum_update]);

                return back()->with(['success' => 'Data nilai dan bobot berhasil diperbarui']);
            } else {
                return back()->with(['error' => 'Gagal memperbarui nilai dan bobot. NIM tidak valid.']);
            }
        }




protected function calculateBobot($nilai)
{
    switch ($nilai) {
        case 'A':
            return 4.0;
        case 'A-':
            return 3.7;
        case 'B+':
            return 3.3;
        case 'B':
            return 3.0;
        case 'B-':
            return 2.7;
        case 'C+':
            return 2.3;
        case 'C':
            return 2.0;
        case 'D':
            return 1.0;
        case 'E':
            return 0.0;
        default:
            return 0.0;
    }
}

public function adddosen(Request $request)
{
    try {
        $request->validate([
            'nidn' => 'required|string',
            'nama_dosen' => 'required|string',
            'password' => 'required|string',
        ]);

        DB::table('dosen')->insert([
            'nidn' => $request->input('nidn'),
            'nama_dosen' => $request->input('nama_dosen'),
        ]);

        DB::table('users')->insert([
            'idn' => $request->input('nidn'),
            'password' => bcrypt($request->input('password')),
            'role' => 'dosen',
        ]);

        return redirect()->route('admin.home')->with('success', 'Dosen added successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors([$e->getMessage()]);
    }
}

public function updatenamadosen(Request $request)
{
    $request->validate([
        'nidn' => 'required',
        'nama_dosen' => 'required|string',
        'password' => 'required|string'
    ]);

    $nidn = $request->input('nidn');
    $nama_dosen = $request->input('nama_dosen');
    $password = $request->input('password');
    DB::table('dosen')->where('nidn', $nidn)->update(['nama_dosen' => $nama_dosen]);
    DB::table('users')->where('idn', $nidn)->update(['password' => bcrypt($password)]);
    return back()->with('success', 'Data dosen berhasil diperbarui');
}

public function uploadMatakuliah(Request $request)
{
    try {
        $request->validate([
            'fileUpload' => 'required|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('fileUpload');
        $csvData = array_map('str_getcsv', file($file->path()));
        $headers = array_map('trim', array_shift($csvData));

        $expectedHeaders = ['id', 'kode_matakuliah', 'nama_matakuliah', 'sks', 'semester', 'bahan_kajian', 'cpl', 'created_at', 'updated_at'];
        if ($headers !== $expectedHeaders) {
            throw new \Exception('Invalid CSV format. Please check the column headers.');
        }

        DB::beginTransaction();
        try {
            foreach ($csvData as $row) {
                list($id, $kode_matakuliah, $nama_matakuliah, $sks, $semester, $bahan_kajian, $cpl, $created_at, $updated_at) = $row;
                $existingRecord = DB::table('matakuliah')
                    ->where('id', $id)
                    ->where('kode_matakuliah', $kode_matakuliah)
                    ->first();

                if ($existingRecord) {
                    DB::table('matakuliah')
                        ->where('id', $id)
                        ->where('kode_matakuliah', $kode_matakuliah)
                        ->update([
                            'nama_matakuliah' => $nama_matakuliah,
                            'sks' => $sks,
                            'semester' => $semester,
                            'bahan_kajian' => $bahan_kajian,
                            'cpl' => $cpl,
                        ]);
                } else {
                    DB::table('matakuliah')->insert([
                        'id' => $id,
                        'kode_matakuliah' => $kode_matakuliah,
                        'nama_matakuliah' => $nama_matakuliah,
                        'sks' => $sks,
                        'semester' => $semester,
                        'bahan_kajian' => $bahan_kajian,
                        'cpl' => $cpl,
                    ]);
                }
            }
            DB::commit();

               return redirect()->back()->with('success', 'File matakuliah berhasil diunggah!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    } catch (\Exception $e) {
        return redirect()->back()->withErrors([$e->getMessage()]);
    }
}

public function deleteMatakuliah(Request $request, $kode_matakuliah)
{
    try {
    // Cek apakah matakuliah dengan kode_matakuliah yang sama sudah ada di transkrip
    $matakuliahInTranskrip = DB::table('matakuliah')
        ->join('transkrip_mahasiswa', 'matakuliah.kode_matakuliah', '=', 'transkrip_mahasiswa.kode_matakuliah')
        ->where('matakuliah.kode_matakuliah', $kode_matakuliah)
        ->get();
    // Jika sudah ada di transkrip, berikan pesan kesalahan
    if ($matakuliahInTranskrip->isNotEmpty()) {
        return back()->with('error', 'Matakuliah tidak dapat dihapus karena sudah ada di transkrip');
    }

    // Jika tidak ada di transkrip, lanjutkan dengan menghapus data matakuliah
    DB::table('matakuliah')->where('kode_matakuliah', $kode_matakuliah)->delete();

    // Berikan pesan sukses
    return back()->with('success', 'Matakuliah berhasil dihapus')->with('alert', 'success');
    } catch (\Exception $e) {
          // Tangani kesalahan dan berikan pesan error
          return back()->with('error', 'Gagal menghapus matakuliah: ' . $e->getMessage());
        }
}



public function updateMatakuliah(Request $request){
    $request->validate([
        'kode_matakuliah' => 'required',
        'nama_matakuliah' => 'required|string',
        'semester' => 'required|string',
        'bahan_kajian' => 'required|string',
        'cpl' => 'required|string',
    ]);
    $kode_matakuliah = $request->input('kode_matakuliah');
    $nama_matakuliah = $request->input('nama_matakuliah');
    $semester = $request->input('semester');
    $bahan_kajian = $request->input('bahan_kajian');
    $cpl = $request->input('cpl');
    DB::table('matakuliah')->where('kode_matakuliah', $kode_matakuliah)->update(['kode_matakuliah'=> $kode_matakuliah,'nama_matakuliah' => $nama_matakuliah, 'semester' => $semester, 'bahan_kajian' => $bahan_kajian, 'cpl' => $cpl]);
    return back()->with('success', 'Data dosen berhasil diperbarui');

}
}
