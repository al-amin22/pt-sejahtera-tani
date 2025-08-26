<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    public function index()
    {
        try {
            $absensis = Absensi::all();
            $absensiGetTanggal = Absensi::select('tanggal')->get();
            return view('staff.absensi.index', compact('absensis', 'absensiGetTanggal'));
        } catch (\Exception $e) {
            return view('staff.absensi.index', ['error' => 'Gagal mengambil data']);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'tanggal' => 'required|date',
                'video'   => 'nullable|file|mimes:mp4,mov,avi|max:2048000', // max 2GB
                'foto'    => 'nullable|file|mimes:jpg,jpeg,png|max:512000000', // max 50MB
            ]);

            // Simpan file video jika ada
            if ($request->hasFile('video')) {
                $videoName = time() . '_' . $request->file('video')->getClientOriginalName();
                $request->file('video')->move(public_path('video'), $videoName);
                $validatedData['video'] = 'video/' . $videoName;
            }

            // Simpan file foto jika ada
            if ($request->hasFile('foto')) {
                $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
                $request->file('foto')->move(public_path('foto'), $fotoName);
                $validatedData['foto'] = 'foto/' . $fotoName;
            }

            Absensi::create($validatedData);

            return redirect()->route('staff.absensi.index')->with('success', 'Data absensi berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('staff.absensi.index')->with('error', 'Gagal menambahkan data absensi');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $absensi = Absensi::findOrFail($id);

            $validatedData = $request->validate([
                // 'tanggal' => 'required|date',
                'video'   => 'nullable|file|mimes:mp4,mov,avi|max:2048000',
                'foto'    => 'nullable|file|mimes:jpg,jpeg,png|max:512000000',
            ]);

            // Simpan file video baru jika diupload
            if ($request->hasFile('video')) {
                $videoName = time() . '_' . $request->file('video')->getClientOriginalName();
                $request->file('video')->move(public_path('video'), $videoName);
                $validatedData['video'] = 'video/' . $videoName;
            }

            // Simpan file foto baru jika diupload
            if ($request->hasFile('foto')) {
                $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
                $request->file('foto')->move(public_path('foto'), $fotoName);
                $validatedData['foto'] = 'foto/' . $fotoName;
            }

            $absensi->update($validatedData);

            return redirect()->route('staff.absensi.index')->with('success', 'Data absensi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('staff.absensi.index')->with('error', 'Gagal memperbarui data absensi');
        }
    }


    public function show($id)
    {
        try {
            $absensi = Absensi::findOrFail($id);
            return view('staff.absensi.show', compact('absensi'));
        } catch (\Exception $e) {
            return redirect()->route('staff.absensi.index')->with('error', 'Gagal mengambil data absensi');
        }
    }

    public function destroy($id)
    {
        try {
            $absensi = Absensi::findOrFail($id);
            $absensi->delete();

            return redirect()->route('staff.absensi.index')->with('success', 'Data absensi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('staff.absensi.index')->with('error', 'Gagal menghapus data absensi');
        }
    }
}
