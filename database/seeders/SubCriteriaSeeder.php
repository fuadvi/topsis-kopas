<?php

namespace Database\Seeders;

use App\Models\SubCriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcreteria = [
            [
                'id' =>1,
                'name' => 'Dibawah Rata'
            ],
            [
                'id' => 2,
                'name' => 'Rata - Rata'
            ],
            [
                'id' => 3,
                'name' => 'Diatas rata-rata'
            ],
            [
                'id' => 4,
                'name' => 'Baik'
            ],
            [
                'id' => 5,
                'name' => 'Luar Biasa'
            ],
        ];

        SubCriteria::insert($subcreteria);
    }
}
