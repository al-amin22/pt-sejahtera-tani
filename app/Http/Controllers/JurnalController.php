<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index()
    {
        $jurnal = Jurnal::with(['transaksi', 'user'])->get();
        return view('jurnal.index', compact('jurnal'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'keterangan' => 'nullable',
                'transaksi_id' => 'required|integer',
                'user_id' => 'required|integer'
            ]);

            Jurnal::create($request->all());

            return redirect()->back()->with('success', 'Jurnal berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan jurnal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $jurnal = Jurnal::findOrFail($id);
            $jurnal->update($request->all());

            return redirect()->back()->with('success', 'Jurnal berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui jurnal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $jurnal = Jurnal::findOrFail($id);
            $jurnal->delete();

            return redirect()->back()->with('success', 'Jurnal berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jurnal: ' . $e->getMessage());
        }
    }
}
