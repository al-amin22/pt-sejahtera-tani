<?php

namespace App\Http\Controllers;

use App\Models\ArusKas;
use Illuminate\Http\Request;

class ArusKasController extends Controller
{
    public function index()
    {
        $arus_kas = ArusKas::with('transaksi')->get();
        return view('arus_kas.index', compact('arus_kas'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'jenis' => 'required',
                'jumlah' => 'required|numeric',
                'transaksi_id' => 'required|integer'
            ]);

            ArusKas::create($request->all());

            return redirect()->back()->with('success', 'Arus kas berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan arus kas: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $arus = ArusKas::findOrFail($id);
            $arus->update($request->all());

            return redirect()->back()->with('success', 'Arus kas berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui arus kas: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $arus = ArusKas::findOrFail($id);
            $arus->delete();

            return redirect()->back()->with('success', 'Arus kas berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus arus kas: ' . $e->getMessage());
        }
    }
}
