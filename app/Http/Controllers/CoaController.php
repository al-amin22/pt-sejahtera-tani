<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    public function index()
    {
        $coa = Coa::orderBy('kode')->get();

        return view('coa.index', compact('coa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:coa,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'string', 'max:100'],
            'saldo_awal' => ['required', 'numeric'],
        ]);

        Coa::create($validated);

        return redirect()->back()->with('success', 'CoA berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $coa = Coa::findOrFail($id);

        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:coa,kode,' . $coa->id],
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'string', 'max:100'],
            'saldo_awal' => ['required', 'numeric'],
        ]);

        $coa->update($validated);

        return redirect()->back()->with('success', 'CoA berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Coa::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'CoA berhasil dihapus.');
    }
}
