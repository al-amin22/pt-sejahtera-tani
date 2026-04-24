<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksi;
use App\Models\Rekening;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', '');
        $rekeningId = $request->input('rekening_id', '');

        $query = Transaksi::with(['details', 'details.dariRekening', 'details.keRekening', 'user'])
            ->orderBy('tanggal_transaksi', 'desc');

        if ($tahun) {
            $query->whereYear('tanggal_transaksi', $tahun);
        }

        if ($bulan) {
            $query->whereMonth('tanggal_transaksi', $bulan);
        }

        $transaksi = $query->get();

        $filteredTransaksi = $transaksi->map(function ($trx) {
            $trx->filteredDetails = $trx->details->filter(function ($detail) {
                return $detail->dari_rekening_id == 3 && $detail->ke_rekening_id == 4;
            });

            return $trx;
        })->filter(function ($trx) {
            return $trx->filteredDetails->isNotEmpty();
        });

        $rekening = Rekening::all();

        $transaksiPerBulan = $filteredTransaksi->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_transaksi)->format('Y-m');
        });

        $saldoBulanan = [];
        $totalPemasukan = 0;
        $totalPengeluaran = 0;

        foreach ($transaksiPerBulan as $bulanKey => $transaksiBulan) {
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

            $saldoBulanan[$bulanKey] = [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $pemasukan - $pengeluaran,
            ];
        }

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        $operasionalAceh = DetailTransaksi::where('dari_rekening_id', 3)->sum('subtotal');

        return View::make('staff.transaksi.index', compact(
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
        $validated = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string',
            'detail' => 'required|array|min:1',
            'detail.*.id' => 'nullable|integer',
            'detail.*.jenis' => 'nullable|in:pemasukan,pengeluaran',
            'detail.*.keterangan' => 'nullable|string',
            'detail.*.nama_barang' => 'required|string',
            'detail.*.jumlah' => 'required|numeric',
            'detail.*.satuan' => 'nullable|string',
            'detail.*.harga' => 'required|numeric',
            'detail.*.referensi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:9999999999',
        ]);

        try {
            $transaksi = Transaksi::create([
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'user_id' => Auth::id(),
                'keterangan' => $validated['keterangan'] ?? null,
                'total' => 0,
            ]);

            $total = 0;

            foreach ($validated['detail'] as $item) {
                $subtotal = $item['jumlah'] * $item['harga'];

                if (($item['jenis'] ?? 'pengeluaran') === 'pemasukan') {
                    $total -= $subtotal;
                } else {
                    $total += $subtotal;
                }

                $filePath = null;
                if (isset($item['referensi']) && is_object($item['referensi']) && method_exists($item['referensi'], 'move')) {
                    $notaPath = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'nota';
                    if (! is_dir($notaPath)) {
                        @mkdir($notaPath, 0777, true);
                    }

                    $fileName = time() . '_' . $item['referensi']->getClientOriginalName();
                    $item['referensi']->move($notaPath, $fileName);
                    $filePath = 'nota/' . $fileName;
                }

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'nama_barang' => $item['nama_barang'],
                    'jenis' => $item['jenis'] ?? 'pengeluaran',
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'satuan' => $item['satuan'] ?? '-',
                    'subtotal' => $subtotal,
                    'mata_uang_id' => 1,
                    'keterangan' => $item['keterangan'] ?? null,
                    'referensi' => $filePath,
                    'tanggal_transaksi' => $validated['tanggal_transaksi'],
                    'dari_rekening_id' => 3,
                    'ke_rekening_id' => 4,
                    'user_id' => Auth::id(),
                ]);
            }

            $transaksi->update(['total' => $total]);

            return Redirect::back()->with('success', 'Transaksi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string',
            'detail' => 'required|array|min:1',
            'detail.*.id' => 'nullable|integer',
            'detail.*.jenis' => 'nullable|in:pemasukan,pengeluaran',
            'detail.*.nama_barang' => 'required|string',
            'detail.*.jumlah' => 'required|numeric',
            'detail.*.satuan' => 'nullable|string',
            'detail.*.harga' => 'required|numeric',
            'detail.*.referensi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->update([
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'keterangan' => $validated['keterangan'] ?? null,
                'user_id' => Auth::id(),
            ]);

            $total = 0;

            $deletedDetailIds = $request->input('deleted_detail_ids', []);
            foreach ((array) $deletedDetailIds as $deletedDetailId) {
                DetailTransaksi::findOrFail($deletedDetailId)->delete();
            }

            foreach ($validated['detail'] as $item) {
                $subtotal = $item['jumlah'] * $item['harga'];

                if (($item['jenis'] ?? 'pengeluaran') === 'pemasukan') {
                    $total -= $subtotal;
                } else {
                    $total += $subtotal;
                }

                $filePath = null;
                if (isset($item['referensi']) && is_object($item['referensi']) && method_exists($item['referensi'], 'move')) {
                    $notaPath = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'nota';
                    if (! is_dir($notaPath)) {
                        @mkdir($notaPath, 0777, true);
                    }

                    $fileName = time() . '_' . $item['referensi']->getClientOriginalName();
                    $item['referensi']->move($notaPath, $fileName);
                    $filePath = 'nota/' . $fileName;
                }

                if (! empty($item['id'])) {
                    $detail = DetailTransaksi::findOrFail($item['id']);
                    if ($detail) {
                        $detail->update([
                            'nama_barang' => $item['nama_barang'],
                            'jenis' => $item['jenis'] ?? 'pengeluaran',
                            'jumlah' => $item['jumlah'],
                            'satuan' => $item['satuan'] ?? null,
                            'harga' => $item['harga'],
                            'subtotal' => $subtotal,
                            'mata_uang_id' => 1,
                            'dari_rekening_id' => 3,
                            'ke_rekening_id' => 4,
                            'keterangan' => $item['keterangan'] ?? null,
                            'referensi' => $filePath ?? $detail->referensi,
                            'tanggal_transaksi' => $validated['tanggal_transaksi'],
                        ]);
                    }
                } else {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'nama_barang' => $item['nama_barang'],
                        'jenis' => $item['jenis'] ?? 'pengeluaran',
                        'jumlah' => $item['jumlah'],
                        'satuan' => $item['satuan'] ?? '-',
                        'harga' => $item['harga'],
                        'subtotal' => $subtotal,
                        'mata_uang_id' => 1,
                        'dari_rekening_id' => 3,
                        'ke_rekening_id' => 4,
                        'keterangan' => $item['keterangan'] ?? null,
                        'referensi' => $filePath,
                        'tanggal_transaksi' => $validated['tanggal_transaksi'],
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            $transaksi->update(['total' => $total]);

            return Redirect::back()->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Gagal update transaksi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['user', 'mataUang', 'detailTransaksi'])->findOrFail($id);

        $filteredDetails = $transaksi->detailTransaksi
            ->where('dari_rekening_id', 3)
            ->where('ke_rekening_id', 4);

        return View::make('staff.transaksi.show', [
            'transaksi' => $transaksi,
            'details' => $filteredDetails,
        ]);
    }

    public function destroy($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            DetailTransaksi::where('transaksi_id', $id)->delete();
            $transaksi->delete();

            return Redirect::back()->with('success', 'Transaksi beserta semua detail berhasil dihapus!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {
        $transaksi = Transaksi::with(['user', 'pemasok', 'mataUang', 'detailTransaksi'])->findOrFail($id);

        $pdf = PDF::loadView('transaksi.export-pdf', compact('transaksi'));

        return $pdf->download('rincian-pengeluaran-pada-tanggal-' . $transaksi->tanggal_transaksi . '.pdf');
    }
}
