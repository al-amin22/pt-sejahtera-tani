<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class KaryawanController extends Controller
{
    public function index()
    {
        try {
            $karyawans = Karyawan::all();
            return View::make('staff.karyawan.index', compact('karyawans'));
        } catch (\Exception $e) {
            return View::make('staff.karyawan.index', ['error' => 'Gagal mengambil data']);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'jobdesk' => 'nullable|string|max:255',
            ]);
            Karyawan::create($validatedData);
            return Redirect::route('staff.karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan');
        } catch (\Exception $e) {
            return Redirect::route('staff.karyawan.index')->with('error', 'Gagal menambahkan data karyawan');
        }
    }

    public function show($id)
    {
        try {
            $karyawan = Karyawan::findOrFail($id);
            return View::make('staff.karyawan.show', compact('karyawan'));
        } catch (\Exception $e) {
            return Redirect::route('staff.karyawan.index')->with('error', 'Gagal mengambil data karyawan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $karyawan = Karyawan::findOrFail($id);
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'jobdesk' => 'nullable|string|max:255',
            ]);
            $karyawan->update($validatedData);
            return Redirect::route('staff.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui');
        } catch (\Exception $e) {
            return Redirect::route('staff.karyawan.index')->with('error', 'Gagal memperbarui data karyawan');
        }
    }

    public function destroy($id)
    {
        try {
            $karyawan = Karyawan::findOrFail($id);
            $karyawan->delete();
            return Redirect::route('staff.karyawan.index')->with('success', 'Data karyawan berhasil dihapus');
        } catch (\Exception $e) {
            return Redirect::route('staff.karyawan.index')->with('error', 'Gagal menghapus data karyawan');
        }
    }
}
