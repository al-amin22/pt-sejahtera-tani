<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Rekening;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DateTime;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Tahun aktif (default tahun berjalan)
        // Ambil parameter filter
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', '');
        $rekeningId = $request->get('rekening_id', '');

        // Query transaksi dengan relasi
        $query = Transaksi::with(['details', 'details.dariRekening', 'details.keRekening', 'user'])
            ->orderBy('tanggal_transaksi', 'asc'); // penting: urutkan dari awal tahun ke akhir

        // Filter berdasarkan tahun
        if ($tahun) {
            $query->whereYear('tanggal_transaksi', $tahun);
        }

        // Filter berdasarkan bulan
        if ($bulan) {
            $query->whereMonth('tanggal_transaksi', $bulan);
        }

        $transaksi = $query->get();

        // Total pemasukan dan pengeluaran tahun ini
        $totalPemasukan = $transaksi
            ->where('total', '<', 0)
            ->sum(function ($trx) {
                return abs($trx->total);
            });

        $totalPengeluaran = $transaksi
            ->where('total', '>=', 0)
            ->reject(function ($trx) {
                // exclude operasional Aceh
                return $trx->dari_rekening_id == 3 && $trx->ke_rekening_id == 4;
            })
            ->sum('total');

        // Saldo akhir tahun
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Hitung statistik alur dana
        $danaDariChina = DetailTransaksi::where('dari_rekening_id', 1)->sum('subtotal');

        $operasionalJambi = DetailTransaksi::where('dari_rekening_id', 2)
            ->where('ke_rekening_id', '=', 4)
            ->sum('subtotal');
        $transferAceh = DetailTransaksi::where('dari_rekening_id', 2)
            ->where('ke_rekening_id', 3)
            ->sum('subtotal');
        $operasionalAceh = DetailTransaksi::where('dari_rekening_id', 3)
            ->where('ke_rekening_id', '=', 4)
            ->sum('subtotal');

        $saldoJambi = DetailTransaksi::where('ke_rekening_id', 2)->sum('subtotal')
            - $operasionalJambi
            - $transferAceh;

        $saldoAceh = DetailTransaksi::where('ke_rekening_id', 3)->sum('subtotal')
            - $operasionalAceh;

        // Data untuk grafik bulanan - PERBAIKAN DI SINI
        $bulananData = [];
        for ($i = 1; $i <= 12; $i++) {
            $pemasukanBulan = Transaksi::where('total', '<', 0)
                ->whereYear('tanggal_transaksi', $tahun)
                ->whereMonth('tanggal_transaksi', $i)
                ->sum('total');

            $pengeluaranBulan = Transaksi::where('total', '>=', 0)
                ->whereYear('tanggal_transaksi', $tahun)
                ->whereMonth('tanggal_transaksi', $i)
                ->sum('total');

            $bulananData[] = [
                'bulan' => DateTime::createFromFormat('!m', $i)->format('M'),
                'pemasukan' => abs($pemasukanBulan),
                'pengeluaran' => $pengeluaranBulan
            ];
        }

        // Transaksi terbaru (5 transaksi terbaru)
        $transaksiTerbaru = Transaksi::with(['details', 'user'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->take(5)
            ->get();

        // Rekening data
        $rekening = Rekening::all();

        return view('dashboard.admin', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'danaDariChina',
            'saldoJambi',
            'saldoAceh',
            'operasionalJambi',
            'transferAceh',
            'operasionalAceh',
            'bulananData', // PASTIKAN INI ADA
            'transaksiTerbaru',
            'rekening',
            'tahun'
        ));
    }
}
