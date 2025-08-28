<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use App\Models\Rekening;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter
        $tahun      = $request->get('tahun', date('Y'));
        $bulan      = $request->get('bulan', '');
        $rekeningId = $request->get('rekening_id', '');

        // Query transaksi dengan relasi
        $query = Transaksi::with([
            'details',
            'details.dariRekening',
            'details.keRekening',
            'user'
        ])->orderBy('tanggal_transaksi', 'asc'); // urutkan dari awal tahun ke akhir

        // Filter berdasarkan tahun
        if ($tahun) {
            $query->whereYear('tanggal_transaksi', $tahun);
        }

        // Filter berdasarkan bulan
        if ($bulan) {
            $query->whereMonth('tanggal_transaksi', $bulan);
        }

        $transaksi = $query->get();

        // Ambil data rekening
        $rekening = Rekening::all();
        $operasionalJambi = DetailTransaksi::where('dari_rekening_id', 2)
            ->where('ke_rekening_id', 4)
            ->sum('subtotal');
        $transferAceh = DetailTransaksi::where('dari_rekening_id', 2)
            ->where('ke_rekening_id', 3)
            ->sum('subtotal');
        $operasionalAceh = DetailTransaksi::where('dari_rekening_id', 3)->sum('subtotal');

        // Hitung statistik alur dana
        $danaDariChina   = DetailTransaksi::where('dari_rekening_id', 1)->sum('subtotal');
        $saldoJambi      = DetailTransaksi::where('ke_rekening_id', 2)->sum('subtotal') - $operasionalJambi - $transferAceh;
        $saldoAceh       = DetailTransaksi::where('ke_rekening_id', 3)->sum('subtotal') - $operasionalAceh;

        // Kelompokkan transaksi per bulan
        $transaksiPerBulan = $transaksi->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_transaksi)->format('Y-m');
        });

        // Hitung saldo bulanan
        $saldoBulanan    = [];
        $saldoSebelumnya = 0;

        foreach ($transaksiPerBulan as $bulanKey => $transaksiBulan) {
            $pemasukan   = 0;
            $pengeluaran = 0;

            foreach ($transaksiBulan as $trx) {
                if ($trx->total < 0) {
                    $pemasukan += abs($trx->total);
                } else {
                    // Hanya pengeluaran dari rekening ID 2
                    if ($trx->dari_rekening_id == 2) {
                        $pengeluaran += $trx->total;
                    }
                }
            }

            $saldoBulanan[$bulanKey] = [
                'pemasukan'   => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo'       => $saldoSebelumnya + $pemasukan - $pengeluaran
            ];

            $saldoSebelumnya = $saldoBulanan[$bulanKey]['saldo'];
        }


        // Urutkan agar tabel tampil dari bulan terbaru → terlama
        $transaksiPerBulan = $transaksiPerBulan->sortKeysDesc();
        $saldoBulanan      = collect($saldoBulanan)->sortKeysDesc()->toArray();

        // Total pemasukan dan pengeluaran tahun ini
        $totalPemasukan = $transaksi->where('total', '<', 0)->sum(function ($trx) {
            return abs($trx->total);
        });
        $totalPengeluaran = $transaksi->where('total', '>=', 0)->sum('total');

        // Saldo akhir tahun
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return view('transaksi.index', compact(
            'transaksi',
            'transaksiPerBulan',
            'saldoBulanan',
            'danaDariChina',
            'operasionalJambi',
            'transferAceh',
            'rekening',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'tahun',
            'bulan',
            'rekeningId',
            'saldoJambi',
            'saldoAceh',
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
            'detail.*.satuan'       => 'required|string',
            'detail.*.jenis'        => 'required|in:pengeluaran,pemasukan',
            'detail.*.jumlah'       => 'required|numeric',
            'detail.*.satuan'       => 'required|string',
            'detail.*.dari_rekening_id' => 'nullable|integer',
            'detail.*.ke_rekening_id'   => 'nullable|integer',
            'detail.*.harga'        => 'required|numeric',
            'detail.*.referensi'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $transaksi = Transaksi::create([
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'user_id'           => 1,
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
                    'jenis'             => $item['jenis'],
                    'jumlah'            => $item['jumlah'],
                    'harga'             => $item['harga'],
                    'satuan'            => $item['satuan'],
                    'subtotal'          => $subtotal,
                    'mata_uang_id'      => 1,
                    'keterangan'        => $item['keterangan'] ?? null,
                    'referensi'         => $filePath,
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                    'dari_rekening_id'  => $item['dari_rekening_id'] ?? null,
                    'ke_rekening_id'    => $item['ke_rekening_id'] ?? null,
                    'user_id'           => 1,
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
            'detail.*.jenis'        => 'required|in:pengeluaran,pemasukan',
            'detail.*.jumlah'      => 'required|numeric',
            'detail.*.satuan'      => 'nullable|string',
            'detail.*.dari_rekening_id' => 'nullable|integer',
            'detail.*.ke_rekening_id' => 'nullable|integer',
            'detail.*.harga'        => 'required|numeric',

            'detail.*.referensi'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->update([
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan'        => $request->keterangan,
                'user_id'           => 1,
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
                            'jenis'        => $item['jenis'],
                            'jumlah'       => $item['jumlah'],
                            'satuan'       => $item['satuan'] ?? null,
                            'harga'        => $item['harga'],
                            'subtotal'     => $subtotal,
                            'mata_uang_id' => 1,
                            'dari_rekening_id' => $item['dari_rekening_id'] ?? null,
                            'ke_rekening_id' => $item['ke_rekening_id'] ?? null,
                            'keterangan'   => $item['keterangan'] ?? null,
                            'referensi'    => $filePath ?? $detail->referensi,
                            'tanggal_transaksi' => $request->tanggal_transaksi,
                        ]);
                    }
                } else {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'nama_barang'  => $item['nama_barang'],
                        'jenis'        => $item['jenis'],
                        'jumlah'       => $item['jumlah'],
                        'satuan'       => $item['satuan'] ?? null,
                        'harga'        => $item['harga'],
                        'subtotal'     => $subtotal,
                        'mata_uang_id' => 1,
                        'dari_rekening_id' => $item['dari_rekening_id'] ?? null,
                        'ke_rekening_id' => $item['ke_rekening_id'] ?? null,
                        'keterangan'   => $item['keterangan'] ?? null,
                        'referensi'    => $filePath,
                        'tanggal_transaksi' => $request->tanggal_transaksi,
                        'user_id'      => 1,
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
        $transaksi = Transaksi::with(['user', 'mataUang', 'detailTransaksi'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
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
