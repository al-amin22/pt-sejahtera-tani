<?php

namespace App\Http\Controllers;

use App\Models\ArusKas;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ArusKasController extends Controller
{
    public function index()
    {
        $arus_kas = ArusKas::with('transaksi')->orderByDesc('tanggal')->get();
        $transaksis = Transaksi::orderByDesc('tanggal_transaksi')->get();

        return View::make('arus_kas.index', compact('arus_kas', 'transaksis'));
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

        return Redirect::back()->with('success', 'Arus kas berhasil ditambahkan.');
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

        return Redirect::back()->with('success', 'Arus kas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        ArusKas::findOrFail($id)->delete();

        return Redirect::back()->with('success', 'Arus kas berhasil dihapus.');
    }
}
