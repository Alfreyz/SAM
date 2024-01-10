<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $dosen = DB::table('dosen')->paginate(5, ['*'], 'page_dosen');
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
        ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn','mahasiswa.angkatan','matakuliah.bahan_kajian', 'matakuliah.cpl', 'transkrip_mahasiswa.bobot')
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
        $matakuliah = $query->paginate(5, ['*'], 'page_matakuliah');
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

        $mahasiswabarQuery = DB::table('mahasiswa')
            ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
            ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
            ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn', 'matakuliah.bahan_kajian', 'matakuliah.cpl', 'transkrip_mahasiswa.bobot')
            ->where('mahasiswa.nidn', $nidn)
            ->where('mahasiswa.status', 'aktif')
            ->get();

        if ($search) {
            $mahasiswabarQuery = $mahasiswabarQuery->filter(function ($item) use ($search) {
                return strpos($item->nim, $search) !== false;
            });
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
            ->leftJoin('mahasiswa', 'transkrip_mahasiswa.nim', '=', 'mahasiswa.nim')
            ->select('transkrip_mahasiswa.*','mahasiswa.nidn','matakuliah.semester', 'matakuliah.nama_matakuliah', 'matakuliah.bahan_kajian', 'matakuliah.cpl');
        $query->where('transkrip_mahasiswa.nim', $nim);
        if ($search) {
            $query->where('matakuliah.nama_matakuliah', 'like', '%' . $search . '%');
        }
        $query->orderBy('matakuliah.semester', 'asc');
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
        $mahasiswabarQuery = DB::table('mahasiswa')
        ->join('transkrip_mahasiswa', 'mahasiswa.nim', '=', 'transkrip_mahasiswa.nim')
        ->join('matakuliah', 'transkrip_mahasiswa.kode_matakuliah', '=', 'matakuliah.kode_matakuliah')
        ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nidn', 'matakuliah.bahan_kajian', 'matakuliah.cpl', 'transkrip_mahasiswa.bobot')
        ->where('mahasiswa.nim', $nim)
        ->first();

        if ($mahasiswabarQuery) {
        $selectedNidn = $mahasiswabarQuery->nidn;

        $resultDosen = $this->datadosen($request, $selectedNidn);

        $bk_group_avg = $resultDosen->data_bk;
        $cpl_group_avg = $resultDosen->data_cpl;

        $alldata = $query->paginate(5);
        $nidn = $alldata->first()->nidn;
        return view('admin.adminmahasiswa', compact('alldata','nidn', 'labels_bk', 'data_bk', 'labels_cpl', 'data_cpl', 'bk_group_avg', 'cpl_group_avg', 'search', 'nim'));
        } else {
            return redirect()->route('error.route')->withErrors(['error' => 'Data not found']);
        }
    }



    // Upload FILE MAHASISWA
    public function uploadfilem(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'fileUpload' => 'required|mimes:csv,txt|max:10240', // Adjust the allowed file types and size
            ]);

            // Get the uploaded file
            $file = $request->file('fileUpload');

            // Process the CSV file
            $csvData = array_map('str_getcsv', file($file->path()));
            $headers = array_map('trim', array_shift($csvData)); // Extract and trim headers

            // Validate CSV headers
            $expectedHeaders = ['nim', 'nidn', 'angkatan', 'status'];

            if ($headers !== $expectedHeaders) {
                throw new \Exception('Invalid CSV format. Please check the column headers.');
            }
            // Process each row of the CSV data
            foreach ($csvData as $row) {
                // Assuming your CSV has these columns: nim, nidn, angkatan, status
                list($nim, $nidn, $angkatan, $status) = $row;

                // Your logic to insert or update the data into the database
                // Example: Insert into 'mahasiswa' table
                DB::table('mahasiswa')->insert([
                    'nim' => $nim,
                    'nidn' => $nidn,
                    'angkatan' => $angkatan,
                    'status' => $status,
                    // id, created_at, and updated_at will be auto-generated by the database
                ]);
            }

            return redirect()->back()->with('success', 'CSV data uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function uploadfiletm(Request $request, $nim)
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

        // Begin a database transaction
        DB::beginTransaction();

        try {
            foreach ($csvData as $row) {
                list($csvNim, $kode_matakuliah, $nilai, $bobot) = $row;

                // Delete records for the specific nim and data combination
                DB::table('transkrip_mahasiswa')
                    ->where('nim', $csvNim)
                    ->where('kode_matakuliah', $kode_matakuliah)
                    ->where('nilai', $nilai)
                    ->where('bobot', $bobot)
                    ->delete();

                // Logic to insert data into the database
                // Example: Insert into 'transkrip_mahasiswa' table
                DB::table('transkrip_mahasiswa')->insert([
                    'nim' => $csvNim,
                    'kode_matakuliah' => $kode_matakuliah,
                    'nilai' => $nilai,
                    'bobot' => $bobot,
                ]);
            }

            // Commit the database transaction
            DB::commit();

            // Redirect to the specified URL with nim parameter
            $redirectUrl = route('admin.adminmahasiswa', ['nim' => $nim]);
            return redirect($redirectUrl)->with('success', 'File uploaded successfully!');
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an error
            DB::rollback();

            // Redirect to the specified URL with nim parameter and error message
            $redirectUrlWithError = route('admin.adminmahasiswa', ['nim' => $nim]);
            return redirect($redirectUrlWithError)->withErrors([$e->getMessage()]);
        }
    } catch (\Exception $e) {
        return redirect()->route('admin.adminmahasiswa')->withErrors([$e->getMessage()]);
    }
}

    public function updatemahasiswa(Request $request)
    {
       // Validasi formulir jika diperlukan
    $request->validate([
        'nim' => 'required',
        'status' => 'required|in:aktif,tidak aktif', // Sesuaikan dengan opsi yang diperlukan
    ]);

    $nim = $request->input('nim');
    $status = $request->input('status');
    // Gunakan DB::table untuk query builder
    DB::table('mahasiswa')->where('nim', $nim)->update(['status' => $status]);
    return back()->with('success', 'Data mahasiswa berhasil diperbarui');
    }

    public function updatenilai(Request $request, $nim)
    {
        // Validasi formulir jika diperlukan
        $request->validate([
            'kode_matakuliah' => 'required',
            'nilai' => 'required|in:A,A-,B+,B,B-,C+,C,D,E', // Sesuaikan dengan opsi yang diperlukan
        ]);

        $kode_matakuliah = $request->input('kode_matakuliah');
        $nilai = $request->input('nilai');

        // Hitung bobot sesuai dengan keterangan yang diberikan
        $bobot = $this->calculateBobot($nilai);

        // Pastikan NIM ada sebelum melakukan update
        if ($nim) {
            // Lakukan update nilai dan bobot
            DB::table('transkrip_mahasiswa')
                ->where('nim', $nim)
                ->where('kode_matakuliah', $kode_matakuliah)
                ->update(['nilai' => $nilai, 'bobot' => $bobot]);

            return back()->with('success', 'Data nilai dan bobot berhasil diperbarui');
        } else {
            return back()->with('error', 'Gagal memperbarui nilai dan bobot. NIM tidak valid.');
        }
    }


protected function calculateBobot($nilai)
{
    // Logika untuk menghitung bobot berdasarkan keterangan yang diberikan
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
