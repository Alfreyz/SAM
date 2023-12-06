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
        $dosen = DB::table('dosen')->get();
        $search = $request->input('search');
        $query = DB::table('matakuliah');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('nama_matakuliah', 'like', '%' . $search . '%')
                    ->orWhere('semester', 'like', '%' . $search . '%');
            });
        }
        $mahasiswabarQuery =  DB::table('mahasiswa')
        ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
        ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn', 'matakuliah.bahan_kajian', 'matakuliah.cpl', 'transkrip_mahasiswa.bobot')
        ->get();
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
        $matakuliah = $query->paginate(5);
        return view('admin.home', compact('dosen', 'labels_bk', 'data_bk', 'labels_cpl', 'data_cpl', 'matakuliah', 'search'));
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

    public function datadosen(Request $request, $nidn)
    {
        $search = $request->input('search');
        $mahasiswaTableQuery = DB::table('mahasiswa')
            ->where('nidn', $nidn);

        if ($search) {
            $mahasiswaTableQuery->where('nim', 'like', '%' . $search . '%');
        }

        $mahasiswaTable = $mahasiswaTableQuery->paginate(5);

        $mahasiswabarQuery =  DB::table('mahasiswa')
            ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
            ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
            ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn', 'matakuliah.bahan_kajian', 'matakuliah.cpl', 'transkrip_mahasiswa.bobot')
            ->where('mahasiswa.nidn', $nidn)
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
        return view('admin.datadosen', compact('mahasiswaTable', 'labels_bk', 'data_bk', 'labels_cpl', 'data_cpl', 'search', 'nidn'));
    }

    public function datamahasiswa(Request $request)
    {
        $nim = $request->input('nim');
        $search = $request->input('search');

        $query = DB::table('transkrip_mahasiswa')
            ->leftJoin('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
            ->select('transkrip_mahasiswa.*','matakuliah.semester', 'matakuliah.nama_matakuliah', 'matakuliah.bahan_kajian', 'matakuliah.cpl');
        $query->where('transkrip_mahasiswa.nim', $nim);
        if ($search) {
            $query->where('matakuliah.nama_matakuliah', 'like', '%' . $search . '%');
        }
        $alldata = $query->get();
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
        // Group data by id
        $mahasiswabarQuery =  DB::table('mahasiswa')
        ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
        ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn', 'matakuliah.bahan_kajian', 'matakuliah.cpl', 'transkrip_mahasiswa.bobot')
        ->where('mahasiswa.nim', $nim)
        ->first();
        $selectedNidn = $mahasiswabarQuery->nidn;
    $resultDosen = $this->datadosen($request, $mahasiswabarQuery->nidn);

    $bk_group_avg = $resultDosen->data_bk;
    $cpl_group_avg = $resultDosen->data_cpl;
        $alldata = $query->paginate(5);
        return view('admin.adminmahasiswa', compact('alldata', 'labels_bk', 'data_bk', 'labels_cpl', 'data_cpl','bk_group_avg','cpl_group_avg', 'search', 'nim'));
    }



}
