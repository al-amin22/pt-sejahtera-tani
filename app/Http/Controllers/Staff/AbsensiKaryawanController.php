<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiKaryawan;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class AbsensiKaryawanController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ambil tanggal dari request atau gunakan hari ini
            $selectedDate = $request->input('tanggal', Carbon::today()->format('Y-m-d'));

            // Cari atau buat record absensi untuk tanggal tersebut
            $absensi = Absensi::where('tanggal', $selectedDate)->first();

            if (! $absensi) {
                $absensi = Absensi::create(['tanggal' => $selectedDate]);
            }

            // Ambil semua karyawan
            $karyawans = Karyawan::all();

            // Ambil data absensi karyawan untuk tanggal tersebut
            $absensiKaryawans = AbsensiKaryawan::with('karyawan')
                ->where('absensi_id', $absensi->id)
                ->get()
                ->keyBy('karyawan_id');

            return View::make('staff.absensi_karyawan.index', compact(
                'absensi',
                'karyawans',
                'absensiKaryawans',
                'selectedDate'
            ));
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Gagal mengambil data: ' . $e->getMessage());
        }
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            DB::beginTransaction();

            $tanggal = $request->input('tanggal');
            $absensi = Absensi::where('tanggal', $tanggal)->first();

            if (! $absensi) {
                $absensi = Absensi::create(['tanggal' => $tanggal]);
            }

            foreach ($request->input('karyawan', []) as $karyawanId => $data) {
                if (!empty($data['status'])) {
                    // Cek apakah absensi karyawan sudah ada
                    $existing = AbsensiKaryawan::where('absensi_id', $absensi->id)
                        ->where('karyawan_id', $karyawanId)
                        ->first();

                    if (!$existing) {
                        // Kalau belum ada → simpan
                        AbsensiKaryawan::create([
                            'absensi_id' => $absensi->id,
                            'karyawan_id' => $karyawanId,
                            'status' => $data['status'],
                            'jam_masuk' => $data['jam_masuk'] ?? null,
                            'jam_keluar' => $data['jam_keluar'] ?? null
                        ]);
                    }
                    // Kalau sudah ada → abaikan (tidak diupdate)
                }
            }

            DB::commit();

            return Redirect::route('staff.absensi_karyawan.index', ['tanggal' => $request->input('tanggal')])
                ->with('success', 'Data absensi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $absensiKaryawan = AbsensiKaryawan::findOrFail($id);
            $absensiKaryawan->delete();

            return Redirect::back()->with('success', 'Data absensi karyawan berhasil dihapus');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
