<?php

namespace App\Http\Controllers;

use App\Models\ArusKas;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class ArusKasController extends Controller
{
    public function index()
    {
        $arus_kas = ArusKas::with('transaksi')->orderByDesc('tanggal')->get();
        $transaksis = Transaksi::orderByDesc('tanggal_transaksi')->get();

        return view('arus_kas.index', compact('arus_kas', 'transaksis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', 'in:operasi,investasi,pendanaan'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'transaksi_id' => ['required', 'exists:transaksi,id'],
        ]);

        ArusKas::create($validated);

        return redirect()->back()->with('success', 'Arus kas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $arus = ArusKas::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', 'in:operasi,investasi,pendanaan'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'transaksi_id' => ['required', 'exists:transaksi,id'],
        ]);

        $arus->update($validated);

        return redirect()->back()->with('success', 'Arus kas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        ArusKas::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Arus kas berhasil dihapus.');
    }
}
