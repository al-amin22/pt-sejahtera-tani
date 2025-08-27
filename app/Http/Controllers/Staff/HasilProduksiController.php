<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HasilProduksi;
use App\Models\AbsensiKaryawan;
use Carbon\Carbon;

class HasilProduksiController extends Controller
{
    public function index()
    {
        $hasilProduksis = HasilProduksi::all();
        // ambil tanggal hari ini
        $today = now()->toDateString(); // tanggal hari ini

        // Ambil data absensi_karyawan tapi hanya yg tanggal = hari ini
        $absensiKaryawans = AbsensiKaryawan::with('absensi')
            ->whereHas('absensi', function ($q) use ($today) {
                $q->whereDate('tanggal', $today);
            })
            ->get();

        return view('staff.hasil_produksi.index', compact('hasilProduksis', 'absensiKaryawans'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'jenis_hasil' => 'required|string|max:255',
                'jumlah' => 'required|integer|min:0',
                'absensi_karyawan_id' => 'required|exists:absensi_karyawan,id',
                'satuan' => 'required|string|max:50',
                'keterangan' => 'nullable|string|max:500',
            ]);

            HasilProduksi::create($validatedData);

            return redirect()->back()->with('success', 'Data hasil produksi Hari ini berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'jenis_hasil' => 'required|string|max:255',
                'jumlah' => 'required|integer|min:0',
                'absensi_karyawan_id' => 'required|exists:absensi_karyawan,id',
                'satuan' => 'required|string|max:50',
                'keterangan' => 'nullable|string|max:500',
            ]);

            $hasilProduksi = HasilProduksi::findOrFail($id);
            $hasilProduksi->update($validatedData);

            return redirect()->back()->with('success', 'Data hasil produksi Hari ini berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $hasilProduksi = HasilProduksi::findOrFail($id);
            $hasilProduksi->delete();

            return redirect()->back()->with('success', 'Data hasil produksi Hari ini berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
