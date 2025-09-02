<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiKaryawan;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiKaryawanController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ambil tanggal dari request atau gunakan hari ini
            $selectedDate = $request->input('tanggal', Carbon::today()->format('Y-m-d'));

            // Cari atau buat record absensi untuk tanggal tersebut
            $absensi = Absensi::firstOrCreate(
                ['tanggal' => $selectedDate],
                ['tanggal' => $selectedDate]
            );

            // Ambil semua karyawan
            $karyawans = Karyawan::all();

            // Ambil data absensi karyawan untuk tanggal tersebut
            $absensiKaryawans = AbsensiKaryawan::with('karyawan')
                ->where('absensi_id', $absensi->id)
                ->get()
                ->keyBy('karyawan_id');

            return view('staff.absensi_karyawan.index', compact(
                'absensi',
                'karyawans',
                'absensiKaryawans',
                'selectedDate'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil data: ' . $e->getMessage());
        }
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            DB::beginTransaction();

            $absensi = Absensi::firstOrCreate(
                ['tanggal' => $request->input('tanggal')],
                ['tanggal' => $request->input('tanggal')]
            );

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

            return redirect()->route('staff.absensi_karyawan.index', ['tanggal' => $request->input('tanggal')])
                ->with('success', 'Data absensi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $absensiKaryawan = AbsensiKaryawan::findOrFail($id);
            $absensiKaryawan->delete();

            return redirect()->back()->with('success', 'Data absensi karyawan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
