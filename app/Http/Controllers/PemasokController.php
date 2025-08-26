<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index()
    {
        $pemasok = Pemasok::all();
        return view('pemasok.index', compact('pemasok'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required',
                'alamat' => 'required',
                'kontak' => 'nullable',
                'kota' => 'required',
            ]);

            Pemasok::create($request->all());

            return redirect()->back()->with('success', 'Pemasok berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan pemasok: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pemasok = Pemasok::findOrFail($id);
            $pemasok->update($request->all());

            return redirect()->back()->with('success', 'Pemasok berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pemasok: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pemasok = Pemasok::findOrFail($id);
            $pemasok->delete();

            return redirect()->back()->with('success', 'Pemasok berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pemasok: ' . $e->getMessage());
        }
    }
}
