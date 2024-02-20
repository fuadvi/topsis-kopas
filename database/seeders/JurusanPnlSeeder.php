<?php

namespace Database\Seeders;

use App\Models\JurusanPNL;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanPnlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = [
            [
                'id' => 1,
                'name' => 'Tik'
            ],
            [
                'id' => 2,
                'name' => 'Tata niaga'
            ],
            [
                'id' => 3,
                'name' => 'Teknik Elektro'
            ],
            [
                'id' => 4,
                'name' => 'Teknik Sipil'
            ],
            [
                'id' => 5,
                'name' => 'Teknik Mesin'
            ],
            [
                'id' => 6,
                'name' => 'Teknik Kimia'
            ],
        ];

        JurusanPNL::insert($jurusan);
    }
}
