<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiKaryawan;
use App\Models\Absensi;
use App\Models\Karyawan;

class AbsensiKaryawanController extends Controller
{
    public function index()
    {
        try {
            $absensiKaryawans = AbsensiKaryawan::with(['absensi', 'karyawan'])->get();
            $absensis = Absensi::all();
            $karyawans = Karyawan::all();
            return view('absensi_karyawan.index', compact('absensiKaryawans', 'absensis', 'karyawans'));
        } catch (\Exception $e) {
            return view('absensi_karyawan.index', ['error' => 'Gagal mengambil data']);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'absensi_id' => 'required|exists:absensi,id',
                'karyawan_id' => 'required|exists:karyawan,id',
                'status' => 'required|string|max:50',
            ]);

            AbsensiKaryawan::create($validatedData);

            return redirect()->route('absensi_karyawan.index')->with('success', 'Data absensi karyawan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('absensi_karyawan.index')->with('error', 'Gagal menambahkan data absensi karyawan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $absensiKaryawan = AbsensiKaryawan::findOrFail($id);

            $validatedData = $request->validate([
                'absensi_id' => 'required|exists:absensi,id',
                'karyawan_id' => 'required|exists:karyawan,id',
                'status' => 'required|string|max:50',
            ]);

            $absensiKaryawan->update($validatedData);

            return redirect()->route('absensi_karyawan.index')->with('success', 'Data absensi karyawan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('absensi_karyawan.index')->with('error', 'Gagal memperbarui data absensi karyawan');
        }
    }

    public function data($id)
    {
        $absensiKaryawan = AbsensiKaryawan::findOrFail($id);
        return response()->json($absensiKaryawan);
    }

    public function destroy($id)
    {
        try {
            $absensiKaryawan = AbsensiKaryawan::findOrFail($id);
            $absensiKaryawan->delete();
            return redirect()->route('absensi_karyawan.index')->with('success', 'Data absensi karyawan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('absensi_karyawan.index')->with('error', 'Gagal menghapus data absensi karyawan');
        }
    }
}
