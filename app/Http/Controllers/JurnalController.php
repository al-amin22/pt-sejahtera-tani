<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Coa;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class JurnalController extends Controller
{
    public function index()
    {
        $jurnal = Jurnal::with('detailJurnal.coa')->orderByDesc('tanggal_jurnal')->get();
        $transaksis = Transaksi::orderByDesc('tanggal_transaksi')->get();
        $users = User::orderBy('name')->get();
        $coas = Coa::orderBy('kode')->get();

        return View::make('jurnal.index', compact('jurnal', 'transaksis', 'users', 'coas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_jurnal' => ['required', 'date'],
            'referensi' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ]);

        Jurnal::create($validated);

        return Redirect::back()->with('success', 'Jurnal berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jurnal = Jurnal::findOrFail($id);

        $validated = $request->validate([
            'tanggal_jurnal' => ['required', 'date'],
            'referensi' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $jurnal->update($validated);

        return Redirect::back()->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function show($id)
    {
        $jurnal = Jurnal::with('detailJurnal.coa')->findOrFail($id);

        return View::make('jurnal.show', compact('jurnal'));
    }

    public function destroy($id)
    {
        Jurnal::findOrFail($id)->delete();

        return Redirect::back()->with('success', 'Jurnal berhasil dihapus.');
    }
}
