<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensis = Absensi::orderByDesc('tanggal')->get();

        return view('absensi.index', compact('absensis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date', 'unique:absensi,tanggal'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,avi', 'max:204800'],
            'foto' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
        ]);

        $publicPath = rtrim(dirname(__DIR__, 3), '\\/') . DIRECTORY_SEPARATOR . 'public';

        if ($request->hasFile('video')) {
            $videoName = time() . '_' . $request->file('video')->getClientOriginalName();
            $request->file('video')->move($publicPath . DIRECTORY_SEPARATOR . 'video', $videoName);
            $validated['video'] = 'video/' . $videoName;
        }

        if ($request->hasFile('foto')) {
            $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move($publicPath . DIRECTORY_SEPARATOR . 'foto', $fotoName);
            $validated['foto'] = 'foto/' . $fotoName;
        }

        Absensi::create($validated);

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => ['required', 'date', 'unique:absensi,tanggal,' . $id],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,avi', 'max:204800'],
            'foto' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
        ]);

        $publicPath = rtrim(dirname(__DIR__, 3), '\\/') . DIRECTORY_SEPARATOR . 'public';

        if ($request->hasFile('video')) {
            $videoName = time() . '_' . $request->file('video')->getClientOriginalName();
            $request->file('video')->move($publicPath . DIRECTORY_SEPARATOR . 'video', $videoName);
            $validated['video'] = 'video/' . $videoName;
        }

        if ($request->hasFile('foto')) {
            $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move($publicPath . DIRECTORY_SEPARATOR . 'foto', $fotoName);
            $validated['foto'] = 'foto/' . $fotoName;
        }

        $absensi->update($validated);

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diperbarui.');
    }


    public function show($id)
    {
        $absensi = Absensi::findOrFail($id);

        return view('absensi.show', compact('absensi'));
    }

    public function destroy($id)
    {
        Absensi::findOrFail($id)->delete();

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
