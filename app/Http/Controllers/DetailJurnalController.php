<?php

namespace App\Http\Controllers;

use App\Models\DetailJurnal;
use Illuminate\Http\Request;

class DetailJurnalController extends Controller
{
    public function index()
    {
        $detail_jurnal = DetailJurnal::with(['jurnal', 'coa'])->get();
        return view('detail_jurnal.index', compact('detail_jurnal'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'jurnal_id' => 'required|integer',
                'coa_id' => 'required|integer',
                'debit' => 'required|numeric',
                'kredit' => 'required|numeric'
            ]);

            DetailJurnal::create($request->all());

            return redirect()->back()->with('success', 'Detail jurnal berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan detail jurnal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $detail = DetailJurnal::findOrFail($id);
            $detail->update($request->all());

            return redirect()->back()->with('success', 'Detail jurnal berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui detail jurnal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $detail = DetailJurnal::findOrFail($id);
            $detail->delete();

            return redirect()->back()->with('success', 'Detail jurnal berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus detail jurnal: ' . $e->getMessage());
        }
    }
}
