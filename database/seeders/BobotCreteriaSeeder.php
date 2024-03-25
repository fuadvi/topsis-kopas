<?php

namespace Database\Seeders;

use App\Models\BobotCriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BobotCreteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            [
                'id' => 1,
                'criteria_id' => 1,
                'point' => 1,
                'range' => 3,
                'name' => 'Dibawah rata-rata'
            ],
            [
                'id' => 2,
                'criteria_id' => 1,
                'point' => 2,
                'range' => 6,
                'name' => 'rata-rata'
            ],
            [
                'id' => 3,
                'criteria_id' => 1,
                'point' => 3,
                'range' => 9,
                'name' => 'Diatas rata-rata'
            ],
            [
                'id' => 4,
                'criteria_id' => 1,
                'point' => 4,
                'range' => 12,
                'name' => 'Baik'
            ],
            [
                'id' => 5,
                'criteria_id' => 1,
                'point' => 5,
                'range' => 15,
                'name' => 'Luar Biasa'
            ],
        ];

        BobotCriteria::insert($criteria);
    }
}
