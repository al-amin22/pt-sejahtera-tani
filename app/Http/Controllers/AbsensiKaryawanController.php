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
        $absensiKaryawans = AbsensiKaryawan::with(['absensi', 'karyawan'])->get();
        $absensis = Absensi::orderByDesc('tanggal')->get();
        $karyawans = Karyawan::orderBy('nama')->get();

        return view('absensi_karyawan.index', compact('absensiKaryawans', 'absensis', 'karyawans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'absensi_id' => ['required', 'exists:absensi,id'],
            'karyawan_id' => ['required', 'exists:karyawan,id'],
            'status' => ['required', 'in:hadir,tidak hadir'],
            'jam_masuk' => ['nullable', 'date_format:H:i'],
            'jam_keluar' => ['nullable', 'date_format:H:i'],
        ]);

        AbsensiKaryawan::create($validated);

        return redirect()->route('absensi_karyawan.index')->with('success', 'Data absensi karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $absensiKaryawan = AbsensiKaryawan::findOrFail($id);

        $validated = $request->validate([
            'absensi_id' => ['required', 'exists:absensi,id'],
            'karyawan_id' => ['required', 'exists:karyawan,id'],
            'status' => ['required', 'in:hadir,tidak hadir'],
            'jam_masuk' => ['nullable', 'date_format:H:i'],
            'jam_keluar' => ['nullable', 'date_format:H:i'],
        ]);

        $absensiKaryawan->update($validated);

        return redirect()->route('absensi_karyawan.index')->with('success', 'Data absensi karyawan berhasil diperbarui.');
    }

    public function data($id)
    {
        return response()->json(AbsensiKaryawan::with(['absensi', 'karyawan'])->findOrFail($id));
    }

    public function destroy($id)
    {
        AbsensiKaryawan::findOrFail($id)->delete();

        return redirect()->route('absensi_karyawan.index')->with('success', 'Data absensi karyawan berhasil dihapus.');
    }
}
