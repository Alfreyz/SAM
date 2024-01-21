<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;
class DosenController extends Controller
{
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

    public function index(Request $request,$selectedAngkatan = null)
    {
        $nidn = Auth::user()->idn;
        $search = $request->input('search');
        $mahasiswaTableQuery = DB::table('mahasiswa')
        ->where('nidn', $nidn);
        $angkatanList = DB::table('mahasiswa')->where('nidn', $nidn)->distinct()->pluck('angkatan');
        $selectedAngkatan = $selectedAngkatan ?? $angkatanList->first();
    if ($search) {
        $mahasiswaTableQuery->where('nim', 'like', '%' . $search . '%');
    }

    $mahasiswaTable = $mahasiswaTableQuery->paginate(5);

    $mahasiswabarQuery =  DB::table('mahasiswa')
        ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
        ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn', 'matakuliah.bahan_kajian', 'matakuliah.cpl', 'transkrip_mahasiswa.bobot')
        ->where('mahasiswa.nidn', $nidn)
        ->where('mahasiswa.status', 'aktif')
        ->where('mahasiswa.angkatan', $selectedAngkatan)
        ->get();

    if ($search) {
        $mahasiswabarQuery->where('mahasiswa.nim', 'like', '%' . $search . '%');
    }

    $allMahasiswaData = $mahasiswabarQuery->groupBy('id');
    $bahan_kajian_data = [];
    $cpl_data = [];
    foreach ($allMahasiswaData as $group) {
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
    $dosen = DB::table('dosen')
    ->select('nama_dosen')
    ->where('nidn', $nidn)
    ->first();
    return view('dosen.home', compact('mahasiswaTable','selectedAngkatan','angkatanList','chartData','dosen', 'labels_bk', 'data_bk', 'labels_cpl', 'data_cpl', 'search', 'nidn'));
    }


    public function datamahasiswa(Request $request)
    {
        $nim = $request->input('nim');
        $search = $request->input('search');

        $query = DB::table('transkrip_mahasiswa')
            ->leftJoin('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
            ->select('transkrip_mahasiswa.*','matakuliah.semester','matakuliah.nama_matakuliah', 'matakuliah.bahan_kajian', 'matakuliah.cpl')->where('transkrip_mahasiswa.nim', $nim);
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('matakuliah.nama_matakuliah', 'like', '%' . $search . '%')
                    ->orWhere('matakuliah.bahan_kajian', 'like', '%' . $search . '%')
                    ->orWhere('matakuliah.cpl', 'like', '%' . $search . '%')
                    ->orWhere('matakuliah.kode_matakuliah', 'like', '%' . $search . '%');
            });
        }

        $transkrip_mahasiswa = $query->get();
        $dataMahasiswa = $transkrip_mahasiswa->groupBy('id');
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
        $mahasiswabarQuery =  DB::table('mahasiswa')
        ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
        ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn', 'mahasiswa.angkatan', 'matakuliah.bahan_kajian', 'matakuliah.cpl', 'transkrip_mahasiswa.bobot')
        ->where('mahasiswa.nim', $nim)
        ->first();
        $selectedNidn = $mahasiswabarQuery->nidn;
        $resultDosen = $this->index($request, $mahasiswabarQuery->angkatan);
        $bk_group_avg = $resultDosen->data_bk;
        $cpl_group_avg = $resultDosen->data_cpl;
        $transkrip_mahasiswa = $query->paginate(5)->appends(['search' => $search]);

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

        return view('dosen.datamahasiswa', compact('transkrip_mahasiswa','dataBK','dataCPL','dataCountBKInMatakuliah','dataCountCPLInMatakuliah', 'labels_bk', 'data_bk', 'labels_cpl', 'data_cpl','bk_group_avg','cpl_group_avg', 'search', 'nim'));
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
}
