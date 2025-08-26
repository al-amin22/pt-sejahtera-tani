<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        $pemasok = Pemasok::all();
        return view('produk.index', compact('produk', 'pemasok'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'kode' => 'required|unique:produk,kode',
                'nama' => 'required',
                'harga' => 'required|numeric',
                'satuan' => 'required|string',
                'pemasok_id' => 'required|exists:pemasok,id',
            ]);

            Produk::create($request->all());

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $produk = Produk::findOrFail($id);
            $produk->update($request->all());

            return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);
            $produk->delete();

            return redirect()->back()->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
