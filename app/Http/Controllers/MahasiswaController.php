<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;
class MahasiswaController extends Controller
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
    public function index(Request $request)
    {
        $idn = Auth::user()->idn;
        $search = $request->input('search');

        $querygetnidn = DB::table('mahasiswa')
            ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
            ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
            ->where('mahasiswa.nim', '=', $idn)
            ->first();

        $selectedNidn = optional($querygetnidn)->nidn;


        $query = DB::table('transkrip_mahasiswa')
            ->leftJoin('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
            ->select('transkrip_mahasiswa.*', 'matakuliah.semester', 'matakuliah.nama_matakuliah', 'matakuliah.bahan_kajian', 'matakuliah.cpl')
            ->where('transkrip_mahasiswa.nim', $idn);

        if ($search) {
            $query->where('matakuliah.nama_matakuliah', 'like', '%' . $search . '%');
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

                foreach ($cpl as $cplItem) {
                    $cpl_data[$cplItem][] = $data->bobot;
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

        $mahasiswabar1Query =  DB::table('mahasiswa')
            ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
            ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
            ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn', 'matakuliah.bahan_kajian', 'matakuliah.cpl', 'transkrip_mahasiswa.bobot')
            ->where('mahasiswa.nidn', $selectedNidn)
            ->where('mahasiswa.angkatan', $querygetnidn->angkatan)
            ->get();

        $bahan_kajian_data_avg = [];
        $cpl_data_avg = [];

        foreach ($mahasiswabar1Query as $data) {
            $bahan_kajian = explode(',', $data->bahan_kajian);
            $cpl = explode(',', $data->cpl);

            foreach ($bahan_kajian as $bahan) {
                $bahan_kajian_data_avg[$bahan][] = $data->bobot;
            }

            foreach ($cpl as $cplItem) {
                $cpl_data_avg[$cplItem][] = $data->bobot;
            }
        }

        $averages_bk_group = $this->calculateAverages($bahan_kajian_data_avg);
        $averages_cpl_group = $this->calculateAverages($cpl_data_avg);

        $labels_bk_group = [];
        $data_bk_group = [];
        foreach ($averages_bk_group as $bahan => $average) {
            $labels_bk_group[] = $bahan;
            $data_bk_group[] = $average;
        }

        $labels_cpl_group = [];
        $data_cpl_group = [];
        foreach ($averages_cpl_group as $cpl => $average) {
            $labels_cpl_group[] = $cpl;
            $data_cpl_group[] = $average;
        }


        $transkrip_mahasiswa = $query->paginate(5)->appends(['search' => $search]);
        return view('mahasiswa.home', compact('transkrip_mahasiswa', 'labels_bk', 'data_bk', 'labels_cpl', 'data_bk_group', 'data_cpl_group', 'data_cpl', 'search', 'idn'));
    }

}
