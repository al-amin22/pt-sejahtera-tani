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

        // Data untuk ringkasan keuangan
        $totalPemasukan = Transaksi::where('total', '<', 0)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('total');
        $totalPemasukan = abs($totalPemasukan);

        $totalPengeluaran = Transaksi::where('total', '>=', 0)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('total');

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Hitung statistik alur dana
        $danaDariChina = DetailTransaksi::where('dari_rekening_id', 1)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('subtotal');

        $saldoJambi = DetailTransaksi::where('ke_rekening_id', 2)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('subtotal');

        $saldoAceh = DetailTransaksi::where('ke_rekening_id', 3)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('subtotal');

        $operasionalJambi = DetailTransaksi::where('dari_rekening_id', 2)
            ->where('ke_rekening_id', '!=', 3)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('subtotal');

        $transferAceh = DetailTransaksi::where('dari_rekening_id', 2)
            ->where('ke_rekening_id', 3)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('subtotal');

        $operasionalAceh = DetailTransaksi::where('dari_rekening_id', 3)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('subtotal');

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
