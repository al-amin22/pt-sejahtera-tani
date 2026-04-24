<?php

namespace App\Http\Controllers;

use App\Models\MataUang;
use Illuminate\Http\Request;

class MataUangController extends Controller
{
    public function index()
    {
        $mata_uang = MataUang::orderBy('kode')->get();

        return view('mata_uang.index', compact('mata_uang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:mata_uang,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'kurs' => ['required', 'numeric', 'min:0'],
        ]);

        MataUang::create($validated);

        return redirect()->back()->with('success', 'Mata uang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $mata_uang = MataUang::findOrFail($id);

        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:mata_uang,kode,' . $mata_uang->id],
            'nama' => ['required', 'string', 'max:255'],
            'kurs' => ['required', 'numeric', 'min:0'],
        ]);

        $mata_uang->update($validated);

        return redirect()->back()->with('success', 'Mata uang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        MataUang::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Mata uang berhasil dihapus.');
    }
}
