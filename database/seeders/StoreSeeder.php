<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $stores = [
            ['store_name' => 'Head Office', 'region' => 'HO', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 CIBUBUR', 'region' => 'REG 02', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 DAAN MOGOT', 'region' => 'REG 01', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 PASAR BARU', 'region' => 'REG 07', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 PONDOK BETUNG BINTARO', 'region' => 'REG 07', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 CINERE', 'region' => 'REG 06', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 DEPOK', 'region' => 'REG 06', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 GADING SERPONG', 'region' => 'REG 07', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 ISKANDAR BOGOR', 'region' => 'REG 06', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 Q-BIG', 'region' => 'REG 07', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 FATMAWATI', 'region' => 'REG 01', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 PANTAI INDAH KAPUK', 'region' => 'REG 01', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 KOTA HARAPAN INDAH', 'region' => 'REG 02', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 CIBARUSAH', 'region' => 'REG 02', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 SILIWANGI PAMULANG', 'region' => 'REG 07', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 PESANGGRAHAN', 'region' => 'REG 01', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 TELUKJAMBE TIMUR', 'region' => 'REG 02', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 BITUNG', 'region' => 'REG 07', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 KALIMALANG BARU', 'region' => 'REG 02', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 CIBINONG', 'region' => 'REG 06', 'no_rek_store' => '0'],
            ['store_name' => 'MITRA10 PONDOK BAMBU', 'region' => 'REG 02', 'no_rek_store' => '0'],
        ];

        DB::table('stores')->insert($stores);
    }
}
