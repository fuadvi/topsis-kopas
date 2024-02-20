<?php

namespace Database\Seeders;

use App\Models\QuestionTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questionTitles = [
            [
                'id' => 1,
                'name' => 'Tes Minat'
            ],
            [
                'id' => 2,
                'name' => 'Tes Bakat'
            ]
        ];

        QuestionTitle::insert($questionTitles);
    }
}
