<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with('pemasok')
            ->orderBy('nama')
            ->get();

        $pemasok = Pemasok::orderBy('nama')->get();

        return view('produk.index', compact('produk', 'pemasok'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:produk,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:50'],
            'harga' => ['required', 'numeric', 'min:0'],
            'pemasok_id' => ['nullable', 'exists:pemasok,id'],
        ]);

        Produk::create($validated);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $produk = Produk::with('pemasok')->findOrFail($id);

        return response()->json($produk);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:produk,kode,' . $produk->id],
            'nama' => ['required', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:50'],
            'harga' => ['required', 'numeric', 'min:0'],
            'pemasok_id' => ['nullable', 'exists:pemasok,id'],
        ]);

        $produk->update($validated);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Produk::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
