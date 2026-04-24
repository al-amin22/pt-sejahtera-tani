<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index()
    {
        $pemasok = Pemasok::orderBy('nama')->get();

        return view('pemasok.index', compact('pemasok'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kontak' => ['nullable', 'string', 'max:150'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:100'],
        ]);

        Pemasok::create($validated);

        return redirect()->back()->with('success', 'Pemasok berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pemasok = Pemasok::findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kontak' => ['nullable', 'string', 'max:150'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:100'],
        ]);

        $pemasok->update($validated);

        return redirect()->back()->with('success', 'Pemasok berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pemasok::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Pemasok berhasil dihapus.');
    }
}
