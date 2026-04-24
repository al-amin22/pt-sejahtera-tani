<?php

namespace App\Http\Controllers;

use App\Models\DetailJurnal;
use App\Models\Jurnal;
use App\Models\Coa;
use Illuminate\Http\Request;

class DetailJurnalController extends Controller
{
    public function index()
    {
        $detail_jurnal = DetailJurnal::with(['jurnal', 'coa'])->orderByDesc('id')->get();
        $jurnals = Jurnal::orderByDesc('tanggal_jurnal')->get();
        $coas = Coa::orderBy('kode')->get();

        return view('detail_jurnal.index', compact('detail_jurnal', 'jurnals', 'coas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jurnal_id' => ['required', 'exists:jurnal,id'],
            'coa_id' => ['required', 'exists:coa,id'],
            'debit' => ['nullable', 'numeric', 'min:0'],
            'kredit' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ]);

        DetailJurnal::create($validated);

        return redirect()->back()->with('success', 'Detail jurnal berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $detail = DetailJurnal::findOrFail($id);

        $validated = $request->validate([
            'jurnal_id' => ['required', 'exists:jurnal,id'],
            'coa_id' => ['required', 'exists:coa,id'],
            'debit' => ['nullable', 'numeric', 'min:0'],
            'kredit' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $detail->update($validated);

        return redirect()->back()->with('success', 'Detail jurnal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DetailJurnal::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Detail jurnal berhasil dihapus.');
    }
}
