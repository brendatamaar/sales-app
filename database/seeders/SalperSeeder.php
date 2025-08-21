<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalperSeeder extends Seeder
{
    public function run(): void
    {
        // Mapping salper to store_name (based on StoreSeeder list)
        $mapping = [
            'NURHIDAYAT NURHIDAYAT' => 'MITRA10 CIBUBUR',
            'AHMAD RIFANDY PB ZONA 1' => 'MITRA10 DAAN MOGOT',
            'ARFAN ARFAN' => 'MITRA10 PASAR BARU',
            'SLAMET ADI SUWITO' => 'MITRA10 PONDOK BETUNG BINTARO',
            'DARIS' => 'MITRA10 CINERE',
            'ARIF DWI PB FW' => 'MITRA10 DEPOK',
            'MOCHAMMAD IRFAN' => 'MITRA10 GADING SERPONG',
            'HENDY ARIYANDI PRIVATE BRAND' => 'MITRA10 ISKANDAR BOGOR',
            'PIQRI ANDIMA PIQRI PB' => 'MITRA10 Q-BIG',
            'ANGGA SEPTIAN POHAN NIRO GRANITE' => 'MITRA10 FATMAWATI',
            'DARUL  ICHWAN' => 'MITRA10 PANTAI INDAH KAPUK',
            'AGUS SUGIANTORO' => 'MITRA10 KOTA HARAPAN INDAH',
            'FAJAR AKBAR ESSENZA' => 'MITRA10 CIBARUSAH',
            'ALAN NIRO' => 'MITRA10 SILIWANGI PAMULANG',
            'MUHAMMAD FIRZIANSYAH' => 'MITRA10 PESANGGRAHAN',
            'SITINAH SIRROTUN NISA PB ZONA 1' => 'MITRA10 TELUKJAMBE TIMUR',
            'FAHMI ADRIANSYAH DIAMOND' => 'MITRA10 BITUNG',
            'BAMBANG SUCIPTO ELEGANZA' => 'MITRA10 KALIMALANG BARU',
            'IQBAL GUNAWAN SUTEJA PB' => 'MITRA10 CIBINONG',
        ];

        foreach ($mapping as $salper => $storeName) {
            $storeId = DB::table('stores')->where('store_name', $storeName)->value('store_id');

            DB::table('salpers')->insert([
                'salper_name' => $salper,
                'store_id' => $storeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
