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
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', '');
        $rekeningId = $request->get('rekening_id', '');

        // =============================
        // Transaksi utama (filtered)
        // =============================
        $query = Transaksi::with(['details', 'details.dariRekening', 'details.keRekening', 'user'])
            ->whereYear('tanggal_transaksi', $tahun)
            ->orderBy('tanggal_transaksi', 'asc');

        if ($bulan) {
            $query->whereMonth('tanggal_transaksi', $bulan);
        }

        $transaksi = $query->get();

        // =============================
        // Statistik Alur Dana
        // =============================
        $danaDariChina = DetailTransaksi::where('dari_rekening_id', 1)
            ->whereHas('transaksi', function ($q) use ($tahun) {
                $q->whereYear('tanggal_transaksi', $tahun);
            })
            ->sum('subtotal');

        $operasionalJambi = DetailTransaksi::where('dari_rekening_id', 2)
            ->where('ke_rekening_id', 4)
            ->whereHas('transaksi', fn($q) => $q->whereYear('tanggal_transaksi', $tahun))
            ->sum('subtotal');

        $transferAceh = DetailTransaksi::where('dari_rekening_id', 2)
            ->where('ke_rekening_id', 3)
            ->whereHas('transaksi', fn($q) => $q->whereYear('tanggal_transaksi', $tahun))
            ->sum('subtotal');

        $operasionalAceh = DetailTransaksi::where('dari_rekening_id', 3)
            ->where('ke_rekening_id', 4)
            ->whereHas('transaksi', fn($q) => $q->whereYear('tanggal_transaksi', $tahun))
            ->sum('subtotal');

        $saldoJambi = DetailTransaksi::where('ke_rekening_id', 2)
            ->whereHas('transaksi', fn($q) => $q->whereYear('tanggal_transaksi', $tahun))
            ->sum('subtotal')
            - $operasionalJambi
            - $transferAceh;

        $saldoAceh = DetailTransaksi::where('ke_rekening_id', 3)
            ->whereHas('transaksi', fn($q) => $q->whereYear('tanggal_transaksi', $tahun))
            ->sum('subtotal')
            - $operasionalAceh;

        // =============================
        // Statistik Total Tahun Ini
        // =============================
        $totalPemasukan = $danaDariChina;
        $totalPengeluaran = $transferAceh + $operasionalJambi;

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // =============================
        // Data untuk Grafik Bulanan
        // =============================
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

        // =============================
        // Transaksi Terbaru
        // =============================
        $transaksiTerbaru = Transaksi::with(['details', 'user'])
            ->whereYear('tanggal_transaksi', $tahun)
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
            'bulananData',
            'transaksiTerbaru',
            'rekening',
            'tahun'
        ));
    }
}
