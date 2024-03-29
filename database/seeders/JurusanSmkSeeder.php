<?php

namespace Database\Seeders;

use App\Models\JurusanSmk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSmkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = [
            [
                'id' => 1,
                'nama' => 'ipa'
            ],
            [
                'id' => 2,
                'nama' => 'ips'
            ],
            [
                'id' => 3,
                'nama' => 'akt'
            ],
            [
                'id' => 4,
                'nama' => 'rpl'
            ],
            [
                'id' => 5,
                'nama' => 'tkj'
            ],
            [
                'id' => 6,
                'nama' => 'tsm'
            ],
        ];

        JurusanSmk::insert($jurusan);
    }
}
