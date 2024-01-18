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
    public function resetRelasi()
    {
        try {
            DB::table('bk_cpl')->truncate(); // Menghapus semua data dari tabel bk_cpl
            return redirect()->back()->with('success', 'Relasi berhasil di-reset.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mereset relasi. Error: ' . $e->getMessage());
        }
    }

    public function uploadBK(Request $request)
    {
        try {
            $request->validate([
                'fileUpload' => 'required|mimes:csv,txt|max:10240',
            ]);

            $file = $request->file('fileUpload');
            $csvData = array_map('str_getcsv', file($file->path()));
            $headers = array_map('trim', array_shift($csvData));

            $expectedHeaders = ['id', 'kode_bk', 'nama_bk','created_at', 'updated_at'];
            if ($headers !== $expectedHeaders) {
                throw new \Exception('Invalid CSV format. Please check the column headers.');
            }

            DB::beginTransaction();
            try {
                foreach ($csvData as $row) {
                    list($id, $kode_bk, $nama_bk,$created_at, $updated_at) = $row;
                    $existingRecord = DB::table('bk')
                        ->where('id', $id)
                        ->where('kode_bk', $kode_bk)
                        ->first();

                    if ($existingRecord) {
                        DB::table('bk')
                            ->where('id', $id)
                            ->where('kode_bk', $kode_bk)
                            ->update([
                                'nama_bk' => $nama_bk,
                            ]);
                    } else {
                        DB::table('bk')->insert([
                            'id' => $id,
                            'kode_bk' => $kode_bk,
                            'nama_bk' => $nama_bk,
                        ]);
                    }
                }
                DB::commit();
                   return redirect()->back()->with('success', 'File bk berhasil diunggah!');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withErrors([$e->getMessage()]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function uploadCPL(Request $request)
    {
        try {
            $request->validate([
                'fileUpload' => 'required|mimes:csv,txt|max:10240',
            ]);

            $file = $request->file('fileUpload');
            $csvData = array_map('str_getcsv', file($file->path()));
            $headers = array_map('trim', array_shift($csvData));

            $expectedHeaders = ['id', 'kode_cpl', 'nama_cpl','created_at', 'updated_at'];
            if ($headers !== $expectedHeaders) {
                throw new \Exception('Invalid CSV format. Please check the column headers.');
            }

            DB::beginTransaction();
            try {
                foreach ($csvData as $row) {
                    list($id, $kode_cpl, $nama_cpl,$created_at, $updated_at) = $row;
                    $existingRecord = DB::table('cpl')
                        ->where('id', $id)
                        ->where('kode_cpl', $kode_cpl)
                        ->first();

                    if ($existingRecord) {
                        DB::table('cpl')
                            ->where('id', $id)
                            ->where('kode_cpl', $kode_cpl)
                            ->update([
                                'nama_cpl' => $nama_cpl,
                            ]);
                    } else {
                        DB::table('cpl')->insert([
                            'id' => $id,
                            'kode_cpl' => $kode_cpl,
                            'nama_cpl' => $nama_cpl,
                        ]);
                    }
                }
                DB::commit();
                   return redirect()->back()->with('success', 'File cpl berhasil diunggah!');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withErrors([$e->getMessage()]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function uploadBK_CPL(Request $request)
    {
        try {
            $request->validate([
                'fileUpload' => 'required|mimes:csv,txt|max:10240',
            ]);

            $file = $request->file('fileUpload');
            $csvData = array_map('str_getcsv', file($file->path()));
            $headers = array_map('trim', array_shift($csvData));

            $expectedHeaders = ['id', 'kode_bk', 'kode_cpl','created_at', 'updated_at'];
            if ($headers !== $expectedHeaders) {
                throw new \Exception('Invalid CSV format. Please check the column headers.');
            }

            DB::beginTransaction();
            try {
                foreach ($csvData as $row) {
                    list($id, $kode_bk, $kode_cpl,$created_at, $updated_at) = $row;
                    $existingRecord = DB::table('bk_cpl')
                        ->where('id', $id)
                        ->where('kode_bk', $kode_bk)
                        ->where('kode_cpl', $kode_cpl)
                        ->first();
                        DB::table('bk_cpl')->insert([
                            'id' => $id,
                            'kode_bk' => $kode_bk,
                            'kode_cpl' => $kode_cpl,
                        ]);
                }
                DB::commit();
                   return redirect()->back()->with('success', 'File bk_cpl berhasil diunggah!');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withErrors([$e->getMessage()]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
    public function deleteBK($id)
    {
        try {
            DB::table('bk')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'BK berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function deleteCPL($id)
    {
        try {
            DB::table('cpl')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'CPL berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('login');
    }
}
