<?php

namespace App\Http\Controllers\Staff;

use App\Models\DetailTransaksi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;

class DetailTransaksiController extends Controller
{
    public function index()
    {
        $details = DetailTransaksi::with(['transaksi', 'keRekening', 'dariRekening', 'mataUang'])
            ->where('dari_rekening_id', 3)
            ->where('ke_rekening_id', 4)
            ->get();

        return view('staff.detail_transaksi.index', compact('details'));
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'transaksi_id'      => 'required|integer',
                'nama_barang'       => 'required|string|max:255',
                'jumlah'            => 'required|integer',
                'harga'             => 'required|numeric',
                'tanggal_transaksi' => 'required|date',
                'jenis'             => 'required|in:pembelian/pengeluaran,pemasukan',
                'mata_uang_id'      => 'required|integer',
                'keterangan'        => 'nullable|string|max:255',
                'dari_rekening_id'  => 'nullable|integer',
                'ke_rekening_id'    => 'nullable|integer',
                'referensi'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480000', // validasi file
            ]);

            // Hitung subtotal
            $subtotal = $request->jumlah * $request->harga;

            // Default null
            $filePath = null;

            // Upload file jika ada
            if ($request->hasFile('referensi')) {
                $fileName = time() . '_' . $request->file('referensi')->getClientOriginalName();
                $request->file('referensi')->move(public_path('nota'), $fileName);
                $filePath = 'nota/' . $fileName;
            }

            // Simpan detail transaksi
            DetailTransaksi::create([
                'transaksi_id'      => $request->transaksi_id,
                'produk_id'         => $request->produk_id,
                'jumlah'            => $request->jumlah,
                'subtotal'          => $subtotal,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'jenis'             => $request->jenis,
                'mata_uang_id'      => $request->mata_uang_id,
                'user_id'           => 'Admin', // lebih bagus ambil dari user login, bukan hardcode "Admin"
                'keterangan'        => $request->keterangan,
                'dari_rekening_id'  => $request->dari_rekening_id,
                'ke_rekening_id'    => $request->ke_rekening_id,
                'referensi'         => $filePath,
            ]);

            // Update total dan tanggal_transaksi di tabel transaksi
            $total = DetailTransaksi::where('transaksi_id', $request->transaksi_id)->sum('subtotal');

            $transaksi = Transaksi::findOrFail($request->transaksi_id);
            $transaksi->update([
                'total'             => $total,
                'tanggal_transaksi' => $request->tanggal_transaksi
            ]);

            return redirect()->back()->with('success', 'Detail transaksi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan detail transaksi: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'produk_id'         => 'required|integer',
                'jumlah'            => 'required|integer',
                'harga'             => 'required|numeric',
                'tanggal_transaksi' => 'required|date',
                'jenis'             => 'required|in:pembelian/pengeluaran,pemasukan',
                'mata_uang_id'      => 'required|integer',
                'keterangan'        => 'nullable|string|max:255',
                'dari_rekening_id'  => 'nullable|integer',
                'ke_rekening_id'    => 'nullable|integer',
                'referensi'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $detail = DetailTransaksi::findOrFail($id);

            // Hitung ulang subtotal
            $subtotal = $request->jumlah * $request->harga;

            // Default path = path lama
            $filePath = $detail->referensi;

            // Kalau ada file baru
            if ($request->hasFile('referensi')) {
                // Hapus file lama
                if ($detail->referensi && file_exists(public_path($detail->referensi))) {
                    unlink(public_path($detail->referensi));
                }

                // Simpan file baru
                $fileName = time() . '_' . $request->file('referensi')->getClientOriginalName();
                $request->file('referensi')->move(public_path('nota'), $fileName);
                $filePath = 'nota/' . $fileName;
            }

            // Update detail transaksi
            $detail->update([
                'produk_id'         => $request->produk_id,
                'jumlah'            => $request->jumlah,
                'subtotal'          => $subtotal,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'jenis'             => $request->jenis,
                'mata_uang_id'      => $request->mata_uang_id,
                'user_id'           => 'Admin',
                'keterangan'        => $request->keterangan,
                'dari_rekening_id'  => $request->dari_rekening_id,
                'ke_rekening_id'    => $request->ke_rekening_id,
                'referensi'         => $filePath,
            ]);

            // Update total transaksi
            $total = DetailTransaksi::where('transaksi_id', $detail->transaksi_id)->sum('subtotal');
            $transaksi = Transaksi::findOrFail($detail->transaksi_id);
            $transaksi->update([
                'total'             => $total,
                'tanggal_transaksi' => $request->tanggal_transaksi
            ]);

            return redirect()->back()->with('success', 'Detail transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui detail transaksi: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $detail = DetailTransaksi::findOrFail($id);
            $transaksiId = $detail->transaksi_id;

            // Hapus record detail transaksi (file tetap ada)
            $detail->delete();

            // Update total transaksi
            $total = DetailTransaksi::where('transaksi_id', $transaksiId)->sum('subtotal');
            $transaksi = Transaksi::findOrFail($transaksiId);
            $transaksi->update([
                'total' => $total
            ]);

            return redirect()->back()->with('success', 'Detail transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus detail transaksi: ' . $e->getMessage());
        }
    }
}
