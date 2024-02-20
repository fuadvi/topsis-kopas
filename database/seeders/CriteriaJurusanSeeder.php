<?php

namespace Database\Seeders;

use App\Models\CriteriaJurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriteriaJurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            [
                'id' => 1,
                'jurusan_pnl_id' => 1,
                'criteria_id' => 1,
            ],
            [
                'id' => 2,
                'jurusan_pnl_id' => 1,
                'criteria_id' => 2,
            ],
            [
                'id' => 3,
                'jurusan_pnl_id' => 1,
                'criteria_id' => 3,
            ],
            [
                'id' => 4,
                'jurusan_pnl_id' => 1,
                'criteria_id' => 4,
            ],
            [
                'id' => 5,
                'jurusan_pnl_id' => 4,
                'criteria_id' => 7,
            ],
            [
                'id' => 6,
                'jurusan_pnl_id' => 4,
                'criteria_id' => 4,
            ],
            [
                'id' => 7,
                'jurusan_pnl_id' => 4,
                'criteria_id' => 2,
            ],
            [
                'id' => 8,
                'jurusan_pnl_id' => 4,
                'criteria_id' => 1,
            ],
            [
                'id' => 9,
                'jurusan_pnl_id' => 3,
                'criteria_id' => 2,
            ],
            [
                'id' => 10,
                'jurusan_pnl_id' => 3,
                'criteria_id' => 5,
            ],
            [
                'id' => 11,
                'jurusan_pnl_id' => 3,
                'criteria_id' => 7,
            ],
            [
                'id' => 12,
                'jurusan_pnl_id' => 6,
                'criteria_id' => 1,
            ],
            [
                'id' => 13,
                'jurusan_pnl_id' => 6,
                'criteria_id' => 2,
            ],
            [
                'id' => 14,
                'jurusan_pnl_id' => 6,
                'criteria_id' => 3,
            ],
            [
                'id' => 15,
                'jurusan_pnl_id' => 5,
                'criteria_id' => 2,
            ],
            [
                'id' => 16,
                'jurusan_pnl_id' => 5,
                'criteria_id' => 7,
            ],
            [
                'id' => 17,
                'jurusan_pnl_id' => 5,
                'criteria_id' => 1,
            ],
            [
                'id' => 18,
                'jurusan_pnl_id' => 5,
                'criteria_id' => 5,
            ],
            [
                'id' => 19,
                'jurusan_pnl_id' => 2,
                'criteria_id' => 2,
            ],
            [
                'id' => 20,
                'jurusan_pnl_id' => 2,
                'criteria_id' => 5,
            ],
            [
                'id' => 21,
                'jurusan_pnl_id' => 2,
                'criteria_id' => 6,
            ],
        ];

        CriteriaJurusan::insert($criteria);
    }
}
