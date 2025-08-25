<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BobotSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['stage' => 'mapping', 'point' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['stage' => 'visit', 'point' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['stage' => 'quotation', 'point' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['stage' => 'won', 'point' => 4, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('bobots')->insert($data);
    }
}
