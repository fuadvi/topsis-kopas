<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'id' => 1,
                'name' => 'Matematika'
            ],
            [
                'id' => 2,
                'name' => 'Bahasa Inggris'
            ],
            [
                'id' => 3,
                'name' => 'Bahasa Indonesia'
            ],
            [
                'id' => 4,
                'name' => 'Fisika'
            ],
            [
                'id' => 5,
                'name' => 'Kimia'
            ],
            [
                'id' => 6,
                'name' => 'Teknologi & Rekayasa'
            ],
            [
                'id' => 7,
                'name' => 'Biologi'
            ],
            [
                'id' => 8,
                'name' => 'Geografi'
            ],
            [
                'id' => 9,
                'name' => 'Akuntansi'
            ],
            [
                'id' => 10,
                'name' => 'Ekonomi'
            ],
            [
                'id' => 11,
                'name' => 'Teknologi Informasi'
            ],
            [
                'id' => 12,
                'name' => 'Pemrograman'
            ],
        ];

        Subject::insert($subjects);
    }
}
