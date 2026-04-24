<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\HasilProduksi;
use App\Models\AbsensiKaryawan;
use App\Models\Absensi;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HasilProduksiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        $absensi = Absensi::whereDate('tanggal', $tanggal)->first();
        $stokKeseluruhan = (float) StokBarang::sum('stok');
        $stokTerpakai = (float) HasilProduksi::sum('jumlah');
        $stokTersisa = max($stokKeseluruhan - $stokTerpakai, 0);

        $data = [
            'tanggal' => $tanggal,
            'totalProduksiPerJenis' => collect(),
            'jumlahKaryawan' => 0,
            'karyawanHadir' => collect(),
            'hasilProduksi' => collect(),
            'absensi' => $absensi,
            'stokTerpakai' => $stokTerpakai,
            'jumlahStokTersisa' => $stokTersisa,
            'jumlahStokKeseluruhan' => $stokKeseluruhan,
        ];

        if ($absensi) {
            $karyawanHadir = AbsensiKaryawan::with('karyawan')
                ->where('absensi_id', $absensi->id)
                ->where('status', 'hadir')
                ->get();

            $hasilProduksi = HasilProduksi::with('absensiKaryawan.karyawan')
                ->whereHas('absensiKaryawan', function ($query) use ($absensi) {
                    $query->where('absensi_id', $absensi->id);
                })
                ->orderBy('jenis_hasil')
                ->get();

            $totalProduksiPerJenis = $hasilProduksi
                ->groupBy('jenis_hasil')
                ->map(fn ($items) => $items->sum('jumlah'));

            $data['totalProduksiPerJenis'] = $totalProduksiPerJenis;
            $data['jumlahKaryawan'] = $karyawanHadir->count();
            $data['karyawanHadir'] = $karyawanHadir;
            $data['hasilProduksi'] = $hasilProduksi;
        }

        return view('staff.hasil_produksi.index', $data);
    }

    public function show($id)
    {
        $hasilProduksi = HasilProduksi::with(['absensiKaryawan.karyawan', 'absensi'])->findOrFail($id);

        return view('staff.hasil_produksi.show', compact('hasilProduksi'));
    }

    public function tambahStok(Request $request)
    {
        $validated = $request->validate([
            'stok' => ['required', 'numeric', 'min:0'],
        ]);

        StokBarang::create([
            'nama_barang' => 'Jernang',
            'stok' => $validated['stok'],
        ]);

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_hasil' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'absensi_karyawan_id' => ['required', 'exists:absensi_karyawan,id'],
            'satuan' => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        HasilProduksi::create($validated);

        return redirect()->back()->with('success', 'Data hasil produksi berhasil disimpan.');
    }

    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'jenis_hasil' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'absensi_id' => ['required', 'exists:absensi,id'],
            'satuan' => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $karyawan = AbsensiKaryawan::where('absensi_id', $validated['absensi_id'])
            ->where('status', 'hadir')
            ->orderBy('id')
            ->first();

        if (! $karyawan) {
            return redirect()->back()->with('error', 'Tidak ada karyawan hadir pada tanggal tersebut.');
        }

        HasilProduksi::create([
            'absensi_karyawan_id' => $karyawan->id,
            'jenis_hasil' => $validated['jenis_hasil'],
            'jumlah' => $validated['jumlah'],
            'satuan' => $validated['satuan'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Data hasil produksi berhasil disimpan.');
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'jenis_hasil' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'absensi_karyawan_id' => ['required', 'exists:absensi_karyawan,id'],
            'satuan' => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $existing = HasilProduksi::where('absensi_karyawan_id', $validated['absensi_karyawan_id'])
            ->where('jenis_hasil', $validated['jenis_hasil'])
            ->first();

        if ($existing) {
            $existing->update($validated);
            return redirect()->back()->with('success', 'Data hasil produksi berhasil diperbarui.');
        }

        HasilProduksi::create($validated);

        return redirect()->back()->with('success', 'Data hasil produksi berhasil disimpan.');
    }

    public function storeMultiple(Request $request)
    {
        $items = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.jenis_hasil' => 'required|string|max:255',
            'items.*.jumlah' => 'required|numeric|min:0',
            'items.*.absensi_karyawan_id' => 'required|exists:absensi_karyawan,id',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.keterangan' => 'nullable|string|max:500',
        ])['items'];

        foreach ($items as $item) {
            HasilProduksi::updateOrCreate(
                [
                    'absensi_karyawan_id' => $item['absensi_karyawan_id'],
                    'jenis_hasil' => $item['jenis_hasil'],
                ],
                $item
            );
        }

        return redirect()->back()->with('success', 'Data hasil produksi massal berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_hasil' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'absensi_karyawan_id' => ['required', 'exists:absensi_karyawan,id'],
            'satuan' => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        HasilProduksi::findOrFail($id)->update($validated);

        return redirect()->back()->with('success', 'Data hasil produksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        HasilProduksi::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data hasil produksi berhasil dihapus.');
    }
}
