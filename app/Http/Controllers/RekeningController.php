<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    public function index()
    {
        $rekening = Rekening::orderBy('nama')->get();

        return view('rekening.index', compact('rekening'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        Rekening::create($validated);

        return redirect()->back()->with('success', 'Rekening berhasil ditambahkan.');
    }

    public function show($id)
    {
        return response()->json(Rekening::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $rekening = Rekening::findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $rekening->update($validated);

        return redirect()->back()->with('success', 'Rekening berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Rekening::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Rekening berhasil dihapus.');
    }
}
