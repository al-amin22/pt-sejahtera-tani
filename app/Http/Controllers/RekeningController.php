<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    public function index()
    {
        $rekening = Rekening::all();
        return view('rekening.index', compact('rekening'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'keterangan' => 'nullable|string',
            ]);

            Rekening::create($request->all());

            return redirect()->back()->with('success', 'Rekening berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Terjadi kesalahan saat menambahkan rekening.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $rekening = Rekening::findOrFail($id);
            return redirect()->back()->with($rekening);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Rekening tidak ditemukan.'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rekening = Rekening::findOrFail($id);
            $request->validate([
                'nama' => 'required|string|max:255',
                'keterangan' => 'nullable|string',
            ]);

            $rekening->update($request->all());
            return redirect()->back()->with('success', 'Rekening berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Terjadi kesalahan saat memperbarui rekening.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $rekening = Rekening::findOrFail($id);
            $rekening->delete();
            return redirect()->back()->with('success', 'Rekening berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Terjadi kesalahan saat menghapus rekening.'], 500);
        }
    }
}
