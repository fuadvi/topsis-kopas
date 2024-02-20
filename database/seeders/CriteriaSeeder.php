<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            [
                'id' => 1,
                'name' => 'Penalaran Visual'
            ],
            [
                'id' => 2,
                'name' => 'Penalaran Numerik'
            ],
            [
                'id' => 3,
                'name' => 'Penalaran Urutan'
            ],
            [
                'id' => 4,
                'name' => 'Pengenalan Spasial'
            ],
            [
                'id' => 5,
                'name' => 'Figural Angka'
            ],
            [
                'id' => 6,
                'name' => 'Sistematisasi'
            ],
            [
                'id' => 7,
                'name' => 'Tiga Dimensi'
            ],
        ];

        Criteria::insert($criteria);
    }
}
