<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class AbsensiController extends Controller
{
    public function index()
    {
        try {
            $absensis = Absensi::all();
            $absensiGetTanggal = Absensi::all(['tanggal']);
            return View::make('staff.absensi.index', compact('absensis', 'absensiGetTanggal'));
        } catch (\Exception $e) {
            return View::make('staff.absensi.index', ['error' => 'Gagal mengambil data']);
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

            $publicPath = rtrim(dirname(__DIR__, 4), '\\/') . DIRECTORY_SEPARATOR . 'public';

            // Simpan file video jika ada
            if ($request->hasFile('video')) {
                $videoName = time() . '_' . $request->file('video')->getClientOriginalName();
                $request->file('video')->move($publicPath . DIRECTORY_SEPARATOR . 'video', $videoName);
                $validatedData['video'] = 'video/' . $videoName;
            }

            // Simpan file foto jika ada
            if ($request->hasFile('foto')) {
                $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
                $request->file('foto')->move($publicPath . DIRECTORY_SEPARATOR . 'foto', $fotoName);
                $validatedData['foto'] = 'foto/' . $fotoName;
            }

            Absensi::create($validatedData);

            return Redirect::route('staff.absensi.index')->with('success', 'Data absensi berhasil ditambahkan');
        } catch (\Exception $e) {
            return Redirect::route('staff.absensi.index')->with('error', 'Gagal menambahkan data absensi');
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

            $publicPath = rtrim(dirname(__DIR__, 4), '\\/') . DIRECTORY_SEPARATOR . 'public';

            // Simpan file video baru jika diupload
            if ($request->hasFile('video')) {
                $videoName = time() . '_' . $request->file('video')->getClientOriginalName();
                $request->file('video')->move($publicPath . DIRECTORY_SEPARATOR . 'video', $videoName);
                $validatedData['video'] = 'video/' . $videoName;
            }

            // Simpan file foto baru jika diupload
            if ($request->hasFile('foto')) {
                $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
                $request->file('foto')->move($publicPath . DIRECTORY_SEPARATOR . 'foto', $fotoName);
                $validatedData['foto'] = 'foto/' . $fotoName;
            }

            $absensi->update($validatedData);

            return Redirect::route('staff.absensi.index')->with('success', 'Data absensi berhasil diperbarui');
        } catch (\Exception $e) {
            return Redirect::route('staff.absensi.index')->with('error', 'Gagal memperbarui data absensi');
        }
    }


    public function show($id)
    {
        try {
            $absensi = Absensi::findOrFail($id);
            return View::make('staff.absensi.show', compact('absensi'));
        } catch (\Exception $e) {
            return Redirect::route('staff.absensi.index')->with('error', 'Gagal mengambil data absensi');
        }
    }

    public function destroy($id)
    {
        try {
            $absensi = Absensi::findOrFail($id);
            $absensi->delete();

            return Redirect::route('staff.absensi.index')->with('success', 'Data absensi berhasil dihapus');
        } catch (\Exception $e) {
            return Redirect::route('staff.absensi.index')->with('error', 'Gagal menghapus data absensi');
        }
    }
}
