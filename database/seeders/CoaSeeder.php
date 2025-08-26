<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoaSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('coa')->insert([
        //     // Aset
        //     ['kode' => '101', 'nama' => 'Kas', 'jenis' => 'aset', 'saldo_awal' => 0, 'created_at' => now(), 'updated_at' => now()],
        //     ['kode' => '102', 'nama' => 'Bank', 'jenis' => 'aset', 'saldo_awal' => 0, 'created_at' => now(), 'updated_at' => now()],
        //     ['kode' => '103', 'nama' => 'Piutang Usaha', 'jenis' => 'aset', 'saldo_awal' => 0, 'created_at' => now(), 'updated_at' => now()],

        //     // Kewajiban
        //     ['kode' => '201', 'nama' => 'Hutang Usaha', 'jenis' => 'kewajiban', 'saldo_awal' => 0, 'created_at' => now(), 'updated_at' => now()],

        //     // Ekuitas
        //     ['kode' => '301', 'nama' => 'Modal Pemilik', 'jenis' => 'ekuitas', 'saldo_awal' => 0, 'created_at' => now(), 'updated_at' => now()],

        //     // Pendapatan
        //     ['kode' => '401', 'nama' => 'Pendapatan Ekspor', 'jenis' => 'pendapatan', 'saldo_awal' => 0, 'created_at' => now(), 'updated_at' => now()],

        //     // Beban
        //     ['kode' => '501', 'nama' => 'Beban Operasional', 'jenis' => 'beban', 'saldo_awal' => 0, 'created_at' => now(), 'updated_at' => now()],
        // ]);
    }
}
