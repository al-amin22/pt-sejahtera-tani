<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AbsensiKaryawanController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MataUangController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\Staff\DetailTransaksiController as StaffDetailTransaksiController;
use App\Http\Controllers\Staff\AbsensiKaryawanController as StaffAbsensiKaryawanController;
use App\Http\Controllers\Staff\TransaksiController as StaffTransaksiController;
use App\Http\Controllers\Staff\AbsensiController as StaffAbsensiController;
use App\Http\Controllers\Staff\KaryawanController as StaffKaryawanController;

Route::get('/', function () {
    return redirect()->route('login');
});


// ✅ Admin dashboard
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', function () {
        return view('dashboard.admin');
    })->name('dashboard');
    // Users
    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::post('users', [UsersController::class, 'store'])->name('users.store');
    Route::get('users/{id}', [UsersController::class, 'show'])->name('users.show');
    Route::put('users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

    // Karyawan
    Route::get('karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::post('karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('karyawan/{id}', [KaryawanController::class, 'show'])->name('karyawan.show');
    Route::put('karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

    // Absensi
    Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('absensi/{id}', [AbsensiController::class, 'show'])->name('absensi.show');
    Route::put('absensi/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');

    // Absensi Karyawan
    Route::get('absensi-karyawan', [AbsensiKaryawanController::class, 'index'])->name('absensi_karyawan.index');
    Route::post('absensi-karyawan', [AbsensiKaryawanController::class, 'store'])->name('absensi_karyawan.store');
    Route::get('absensi-karyawan/{id}', [AbsensiKaryawanController::class, 'show'])->name('absensi_karyawan.show');
    Route::put('absensi-karyawan/{id}', [AbsensiKaryawanController::class, 'update'])->name('absensi_karyawan.update');
    Route::delete('absensi-karyawan/{id}', [AbsensiKaryawanController::class, 'destroy'])->name('absensi_karyawan.destroy');
    Route::get('absensi-karyawan/data', [AbsensiKaryawanController::class, 'data'])->name('absensi_karyawan.data');

    // Transaksi
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::put('transaksi/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    // Detail Transaksi
    Route::get('detail-transaksi', [DetailTransaksiController::class, 'index'])->name('detail_transaksi.index');
    Route::post('detail-transaksi', [DetailTransaksiController::class, 'store'])->name('detail_transaksi.store');
    Route::get('detail-transaksi/{id}', [DetailTransaksiController::class, 'show'])->name('detail_transaksi.show');
    Route::put('detail-transaksi/{id}', [DetailTransaksiController::class, 'update'])->name('detail_transaksi.update');
    Route::delete('detail-transaksi/{id}', [DetailTransaksiController::class, 'destroy'])->name('detail_transaksi.destroy');

    // Mata Uang
    Route::get('mata-uang', [MataUangController::class, 'index'])->name('mata_uang.index');
    Route::post('mata-uang', [MataUangController::class, 'store'])->name('mata_uang.store');
    Route::get('mata-uang/{id}', [MataUangController::class, 'show'])->name('mata_uang.show');
    Route::put('mata-uang/{id}', [MataUangController::class, 'update'])->name('mata_uang.update');
    Route::delete('mata-uang/{id}', [MataUangController::class, 'destroy'])->name('mata_uang.destroy');

    //Rekening
    Route::get('rekening', [RekeningController::class, 'index'])->name('rekening.index');
    Route::post('rekening', [RekeningController::class, 'store'])->name('rekening.store');
    Route::get('rekening/{id}', [RekeningController::class, 'show'])->name('rekening.show');
    Route::put('rekening/{id}', [RekeningController::class, 'update'])->name('rekening.update');
    Route::delete('rekening/{id}', [RekeningController::class, 'destroy'])->name('rekening.destroy');

    //eksport pdf
    Route::get('transaksi/export-pdf', [TransaksiController::class, 'exportPdf'])->name('transaksi.export-pdf');
});

// ✅ Staff dashboard
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard.index');
    })->name('dashboard');

    //transaksi
    Route::get('transaksi', [StaffTransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('transaksi', [StaffTransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('transaksi/{id}', [StaffTransaksiController::class, 'show'])->name('transaksi.show');
    Route::put('transaksi/{id}', [StaffTransaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('transaksi/{id}', [StaffTransaksiController::class, 'destroy'])->name('transaksi.destroy');

    //eksport pdf
    Route::get('transaksi/export-pdf', [StaffTransaksiController::class, 'exportPdf'])->name('transaksi.export-pdf');

    //detail transaksi
    Route::get('detail-transaksi', [StaffDetailTransaksiController::class, 'index'])->name('detail_transaksi.index');
    Route::post('detail-transaksi', [StaffDetailTransaksiController::class, 'store'])->name('detail_transaksi.store');
    Route::get('detail-transaksi/{id}', [StaffDetailTransaksiController::class, 'show'])->name('detail_transaksi.show');
    Route::put('detail-transaksi/{id}', [StaffDetailTransaksiController::class, 'update'])->name('detail_transaksi.update');
    Route::delete('detail-transaksi/{id}', [StaffDetailTransaksiController::class, 'destroy'])->name('detail_transaksi.destroy');

    //absensi karyawan
    Route::get('absensi-karyawan', [StaffAbsensiKaryawanController::class, 'index'])->name('absensi_karyawan.index');
    Route::post('absensi-karyawan', [StaffAbsensiKaryawanController::class, 'store'])->name('absensi_karyawan.store');
    Route::get('absensi-karyawan/{id}', [StaffAbsensiKaryawanController::class, 'show'])->name('absensi_karyawan.show');
    Route::put('absensi-karyawan/{id}', [StaffAbsensiKaryawanController::class, 'update'])->name('absensi_karyawan.update');
    Route::delete('absensi-karyawan/{id}', [StaffAbsensiKaryawanController::class, 'destroy'])->name('absensi_karyawan.destroy');
    Route::get('absensi-karyawan/data', [AbsensiKaryawanController::class, 'data'])->name('absensi_karyawan.data');

    //absensi
    Route::get('absensi', [StaffAbsensiController::class, 'index'])->name('absensi.index');
    Route::post('absensi', [StaffAbsensiController::class, 'store'])->name('absensi.store');
    Route::get('absensi/{id}', [StaffAbsensiController::class, 'show'])->name('absensi.show');
    Route::put('absensi/{id}', [StaffAbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('absensi/{id}', [StaffAbsensiController::class, 'destroy'])->name('absensi.destroy');

    //karyawan
    Route::get('karyawan', [StaffKaryawanController::class, 'index'])->name('karyawan.index');
    Route::post('karyawan', [StaffKaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('karyawan/{id}', [StaffKaryawanController::class, 'show'])->name('karyawan.show');
    Route::put('karyawan/{id}', [StaffKaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('karyawan/{id}', [StaffKaryawanController::class, 'destroy'])->name('karyawan.destroy');
});

// ✅ Finance dashboard
Route::middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/finance/dashboard', function () {
        return view('dashboard.finance');
    })->name('finance.dashboard');
});

// // ================== ADMIN ==================
// Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard.admin'); // buat view dashboard/admin.blade.php
//     })->name('admin.dashboard');

//     Route::resource('users', UsersController::class);
//     Route::resource('karyawan', KaryawanController::class);
//     Route::resource('absensi', AbsensiController::class);
//     Route::resource('absensi-karyawan', AbsensiKaryawanController::class);
//     Route::resource('transaksi', DetailTransaksiController::class);
//     Route::resource('mata-uang', MataUangController::class);
// });


// // ================== STAFF ==================
// Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard.staff'); // buat view dashboard/staff.blade.php
//     })->name('staff.dashboard');

//     Route::resource('absensi', AbsensiController::class)->only(['index', 'create', 'store']);
//     Route::resource('absensi-karyawan', AbsensiKaryawanController::class)->only(['index', 'create', 'store']);
//     Route::resource('karyawan', KaryawanController::class)->only(['index']);
// });


// // ================== FINANCE ==================
// Route::middleware(['auth', 'role:finance'])->prefix('finance')->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard.finance');
//     })->name('finance.dashboard');

//     Route::resource('transaksi', DetailTransaksiController::class);
//     Route::resource('mata-uang', MataUangController::class);
// });
