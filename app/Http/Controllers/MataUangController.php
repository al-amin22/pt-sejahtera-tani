<?php

namespace App\Http\Controllers;

use App\Models\MataUang;
use Illuminate\Http\Request;

class MataUangController extends Controller
{
    public function index()
    {
        $mata_uang = MataUang::all();
        return view('mata_uang.index', compact('mata_uang'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'kode' => 'required|unique:mata_uang,kode',
                'nama' => 'required',
                'simbol' => 'required'
            ]);

            MataUang::create($request->all());

            return redirect()->back()->with('success', 'Mata uang berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan mata uang: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $mata_uang = MataUang::findOrFail($id);
            $mata_uang->update($request->all());

            return redirect()->back()->with('success', 'Mata uang berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui mata uang: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $mata_uang = MataUang::findOrFail($id);
            $mata_uang->delete();

            return redirect()->back()->with('success', 'Mata uang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus mata uang: ' . $e->getMessage());
        }
    }
}
