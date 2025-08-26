<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    public function index()
    {
        $coa = Coa::all();
        return view('coa.index', compact('coa'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'kode' => 'required|unique:coa,kode',
                'nama' => 'required',
                'jenis' => 'required',
                'saldo_awal' => 'required|numeric',
            ]);

            Coa::create($request->all());

            return redirect()->back()->with('success', 'CoA berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan CoA: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $coa = Coa::findOrFail($id);
            $coa->update($request->all());

            return redirect()->back()->with('success', 'CoA berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui CoA: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $coa = Coa::findOrFail($id);
            $coa->delete();

            return redirect()->back()->with('success', 'CoA berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus CoA: ' . $e->getMessage());
        }
    }
}
