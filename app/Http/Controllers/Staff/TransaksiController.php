<?php

namespace App\Http\Controllers\Staff;

use App\Models\Transaksi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use App\Models\Rekening;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', '');
        $rekeningId = $request->get('rekening_id', '');

        // Query transaksi dengan relasi
        $query = Transaksi::with(['details', 'details.dariRekening', 'details.keRekening', 'user'])
            ->orderBy('tanggal_transaksi', 'desc');

        // Filter berdasarkan tahun
        if ($tahun) {
            $query->whereYear('tanggal_transaksi', $tahun);
        }

        // Filter berdasarkan bulan
        if ($bulan) {
            $query->whereMonth('tanggal_transaksi', $bulan);
        }

        // Ambil semua transaksi
        $transaksi = $query->get();

        // ====== 🔹 Filter detail transaksi rekening 3 -> 4
        $filteredTransaksi = $transaksi->map(function ($trx) {
            $trx->filteredDetails = $trx->details->filter(function ($detail) {
                return $detail->dari_rekening_id == 3 && $detail->ke_rekening_id == 4;
            });
            return $trx;
        })->filter(function ($trx) {
            return $trx->filteredDetails->isNotEmpty();
        });

        // Ambil data rekening
        $rekening = Rekening::all();

        // Kelompokkan transaksi per bulan (dari hasil filter)
        $transaksiPerBulan = $filteredTransaksi->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_transaksi)->format('Y-m');
        });

        // Hitung saldo bulanan
        $saldoBulanan = [];
        $totalPemasukan = 0;
        $totalPengeluaran = 0;

        foreach ($transaksiPerBulan as $bulan => $transaksiBulan) {
            $pemasukan = 0;
            $pengeluaran = 0;

            foreach ($transaksiBulan as $trx) {
                foreach ($trx->filteredDetails as $detail) {
                    if ($detail->subtotal < 0) {
                        $pemasukan += abs($detail->subtotal);
                        $totalPemasukan += abs($detail->subtotal);
                    } else {
                        $pengeluaran += $detail->subtotal;
                        $totalPengeluaran += $detail->subtotal;
                    }
                }
            }

            $saldoBulanan[$bulan] = [
                'pemasukan'   => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo'       => $pemasukan - $pengeluaran
            ];
        }

        // Hitung saldo akhir
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Total khusus operasional Aceh (contoh existing)
        $operasionalAceh = DetailTransaksi::where('dari_rekening_id', 3)->sum('subtotal');

        return view('staff.transaksi.index', compact(
            'transaksi',
            'transaksiPerBulan',
            'saldoBulanan',
            'rekening',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'tahun',
            'bulan',
            'rekeningId',
            'operasionalAceh'
        ));
    }


    public function store(Request $request)
    {

        $request->validate([
            'tanggal_transaksi'     => 'required|date',
            'keterangan'            => 'nullable|string',
            'detail.*.id'           => 'nullable|integer',
            'detail.*.keterangan'   => 'nullable|string',
            'detail.*.nama_barang'  => 'required|string',
            'detail.*.jumlah'       => 'required|numeric',
            'detail.*.satuan'       => 'nullable|string',
            'detail.*.harga'        => 'required|numeric',
            'detail.*.referensi'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:9999999999',
        ]);

        try {
            $transaksi = Transaksi::create([
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'user_id'           => Auth::id(),
                'keterangan'        => $request->keterangan,
                'total'             => 0,
            ]);

            $total = 0;

            foreach ($request->detail as $item) {
                $subtotal = $item['jumlah'] * $item['harga'];

                // Jika jenis pemasukan, kurangi total
                if ($item['jenis'] === 'pemasukan') {
                    $total -= $subtotal;
                } else {
                    $total += $subtotal;
                }

                $filePath = null;
                if (isset($item['referensi']) && $item['referensi'] instanceof \Illuminate\Http\UploadedFile) {
                    $fileName = time() . '_' . $item['referensi']->getClientOriginalName();
                    $item['referensi']->move(public_path('nota'), $fileName);
                    $filePath = 'nota/' . $fileName;
                }

                DetailTransaksi::create([
                    'transaksi_id'      => $transaksi->id,
                    'nama_barang'       => $item['nama_barang'],
                    'jenis'             => 'pengeluaran',
                    'jumlah'            => $item['jumlah'],
                    'harga'             => $item['harga'],
                    'satuan'            => $item['satuan'] ?? '-',
                    'subtotal'          => $subtotal,
                    'mata_uang_id'      => 1,
                    'keterangan'        => $item['keterangan'] ?? null,
                    'referensi'         => $filePath,
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                    'dari_rekening_id'  => 3,
                    'ke_rekening_id'    => 4,
                    'user_id'           => Auth::id(),
                ]);
            }

            $transaksi->update(['total' => $total]);

            return back()->with('success', 'Transaksi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_transaksi'     => 'required|date',
            'keterangan'            => 'nullable|string',
            'detail.*.id'           => 'nullable|integer',
            'detail.*.nama_barang'  => 'required|string',
            'detail.*.jumlah'      => 'required|numeric',
            'detail.*.satuan'      => 'nullable|string',
            'detail.*.harga'        => 'required|numeric',
            'detail.*.referensi'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->update([
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan'        => $request->keterangan,
                'user_id'           => Auth::id(),
            ]);

            $total = 0;
            if ($request->has('deleted_detail_ids')) {
                DetailTransaksi::whereIn('id', $request->deleted_detail_ids)->delete();
            }

            foreach ($request->detail as $item) {
                $subtotal = $item['jumlah'] * $item['harga'];

                // Jika jenis pemasukan, kurangi total
                if ($item['jenis'] === 'pemasukan') {
                    $total -= $subtotal;
                } else {
                    $total += $subtotal;
                }

                $filePath = null;
                if (isset($item['referensi']) && $item['referensi'] instanceof \Illuminate\Http\UploadedFile) {
                    $fileName = time() . '_' . $item['referensi']->getClientOriginalName();
                    $item['referensi']->move(public_path('nota'), $fileName);
                    $filePath = 'nota/' . $fileName;
                }

                if (!empty($item['id'])) {
                    $detail = DetailTransaksi::find($item['id']);
                    if ($detail) {
                        $detail->update([
                            'nama_barang'  => $item['nama_barang'],
                            'jenis'        => 'pengeluaran',
                            'jumlah'       => $item['jumlah'],
                            'satuan'       => $item['satuan'] ?? null,
                            'harga'        => $item['harga'],
                            'subtotal'     => $subtotal,
                            'mata_uang_id' => 1,
                            'dari_rekening_id' => 3,
                            'ke_rekening_id' => 4,
                            'keterangan'   => $item['keterangan'] ?? null,
                            'referensi'    => $filePath ?? $detail->referensi,
                            'tanggal_transaksi' => $request->tanggal_transaksi,
                        ]);
                    }
                } else {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'nama_barang'  => $item['nama_barang'],
                        'jenis'        => 'pengeluaran',
                        'jumlah'       => $item['jumlah'],
                        'satuan'       => $item['satuan'] ?? '-',
                        'harga'        => $item['harga'],
                        'subtotal'     => $subtotal,
                        'mata_uang_id' => 1,
                        'dari_rekening_id' => 3,
                        'ke_rekening_id' => 4,
                        'keterangan'   => $item['keterangan'] ?? null,
                        'referensi'    => $filePath,
                        'tanggal_transaksi' => $request->tanggal_transaksi,
                        'user_id'      => Auth::id(),
                    ]);
                }
            }

            $transaksi->update(['total' => $total]);

            return back()->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update transaksi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['user', 'mataUang', 'detailTransaksi'])
            ->findOrFail($id);

        // filter detail transaksi langsung di collection
        $filteredDetails = $transaksi->detailTransaksi
            ->where('dari_rekening_id', 3)
            ->where('ke_rekening_id', 4);

        return view('staff.transaksi.show', [
            'transaksi' => $transaksi,
            'details'   => $filteredDetails
        ]);
    }

    public function destroy($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);

            // Hapus semua detail transaksi yang terkait (file nota tetap ada)
            DetailTransaksi::where('transaksi_id', $id)->delete();

            // Hapus transaksi utama
            $transaksi->delete();

            return redirect()->back()->with('success', 'Transaksi beserta semua detail berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {
        $transaksi = Transaksi::with(['user', 'pemasok', 'mataUang', 'detailTransaksi'])->findOrFail($id);

        $pdf = PDF::loadView('transaksi.export-pdf', compact('transaksi'));
        return $pdf->download('rincian-pengeluaran-pada-tanggal-' . $transaksi->tanggal_transaksi . '.pdf');
    }
}
