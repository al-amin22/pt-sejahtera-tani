<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HasilProduksi;
use App\Models\AbsensiKaryawan;
use App\Models\Absensi;
use App\Models\StokBarang;
use Carbon\Carbon;

class HasilProduksiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        // Ambil absensi di tanggal tersebut
        $absensi = Absensi::whereDate('tanggal', $tanggal)->first();

        // Hitung stok (selalu dihitung meski absensi kosong)
        $jumlahStokKeseluruhan = StokBarang::sum('stok');
        $stokTerpakai = HasilProduksi::where('jenis_hasil', 'Jumlah Jernang Bulat yang Ditumbuk')->sum('jumlah');
        $jumlahStokTersisa = $jumlahStokKeseluruhan - $stokTerpakai;

        // Default kosong
        $data = [
            'tanggal'                => $tanggal,
            'totalProduksiPerJenis'  => [],
            'jumlahKaryawan'         => 0,
            'karyawanHadir'          => [],
            'hasilProduksi'          => [],
            'absensi'                => $absensi,
            'stokTerpakai'           => $stokTerpakai,
            'jumlahStokTersisa'      => $jumlahStokTersisa,
            'jumlahStokKeseluruhan'  => $jumlahStokKeseluruhan,
        ];

        if ($absensi) {
            // Total produksi
            $totalProduksiPerJenis = HasilProduksi::select('jenis_hasil')
                ->selectRaw('SUM(jumlah) as total')
                ->whereHas('absensiKaryawan', function ($q) use ($absensi) {
                    $q->where('absensi_id', $absensi->id);
                })
                ->groupBy('jenis_hasil')
                ->pluck('total', 'jenis_hasil');

            // Karyawan hadir
            $karyawanHadir = AbsensiKaryawan::with('karyawan')
                ->where('absensi_id', $absensi->id)
                ->where('status', 'hadir')
                ->get();

            // Hasil produksi detail
            $hasilProduksi = HasilProduksi::with(['absensiKaryawan.karyawan'])
                ->whereHas('absensiKaryawan', function ($q) use ($absensi) {
                    $q->where('absensi_id', $absensi->id);
                })
                ->orderByRaw("FIELD(jenis_hasil,
        'Jumlah Jernang Bulat yang Ditumbuk',
        'Hasil Tumbukan',
        'Dedak kering kasar',
        'Dedak kering halus',
        'Dedak basah',
        'Hasil bola-bola')")
                ->get();

            $data['totalProduksiPerJenis'] = $totalProduksiPerJenis;
            $data['jumlahKaryawan']        = $karyawanHadir->count();
            $data['karyawanHadir']         = $karyawanHadir;
            $data['hasilProduksi']         = $hasilProduksi;
        }

        return view('staff.hasil_produksi.index', $data);
    }

    public function TambahStok(Request $request)
    {
        try {
            $request->validate([
                'stok' => 'required|numeric|min:0',
            ]);

            StokBarang::create([
                'nama_barang' => 'Jernang',
                'stok'        => $request->stok,
            ]);

            return redirect()->back()->with('success', 'Stok Jernang Bulat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan stok: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'jenis_hasil' => 'required|string|max:255',
                'jumlah' => 'required|numeric|min:0',
                'absensi_karyawan_id' => 'required|exists:absensi_karyawan,id',
                'satuan' => 'required|string|max:50',
                'keterangan' => 'nullable|string|max:500',
            ]);

            // Cek apakah sudah ada data dengan jenis hasil yang sama untuk absensi_karyawan_id yang sama
            $existing = HasilProduksi::where('absensi_karyawan_id', $validatedData['absensi_karyawan_id'])
                ->where('jenis_hasil', $validatedData['jenis_hasil'])
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'Data dengan jenis hasil tersebut sudah ada untuk karyawan ini. Gunakan edit untuk mengubah.')
                    ->withInput();
            }

            HasilProduksi::create($validatedData);

            return redirect()->back()
                ->with('success', 'Data hasil produksi berhasil disimpan.')
                ->with('scrollTo', 'production-section');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function quickStore(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'jenis_hasil' => 'required|string|max:255',
                'jumlah' => 'required|numeric|min:0',
                'absensi_id' => 'required|exists:absensi,id',
                'satuan' => 'required|string|max:50',
                'keterangan' => 'nullable|string|max:500',
            ]);

            // Ambil salah satu karyawan yang hadir pada absensi tersebut
            $karyawan = AbsensiKaryawan::where('absensi_id', $validatedData['absensi_id'])
                ->where('status', 'hadir')
                ->first();

            if (!$karyawan) {
                return redirect()->back()
                    ->with('error', 'Tidak ada karyawan yang hadir pada tanggal tersebut.')
                    ->withInput();
            }

            // Cek apakah data hasil produksi untuk jenis hasil ini sudah ada untuk karyawan tersebut
            $existing = HasilProduksi::where('absensi_karyawan_id', $karyawan->id)
                ->where('jenis_hasil', trim($validatedData['jenis_hasil']))
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'Data hasil produksi untuk jenis ini sudah ada.')
                    ->withInput();
            }

            // Simpan data hasil produksi
            HasilProduksi::create([
                'absensi_karyawan_id' => $karyawan->id,
                'jenis_hasil' => trim($validatedData['jenis_hasil']),
                'jumlah' => $validatedData['jumlah'],
                'satuan' => $validatedData['satuan'],
                'keterangan' => $validatedData['keterangan'],
            ]);

            return redirect()->back()
                ->with('success', 'Data hasil produksi berhasil disimpan.')
                ->with('scrollTo', 'production-section');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
