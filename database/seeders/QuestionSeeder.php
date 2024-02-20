<?php

namespace Database\Seeders;

use App\Models\Choice;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $question = Question::factory(5)->create();
            $question->each(function ($question){
                Choice::factory(rand(1,5))->create([
                    'question_id' => $question->id
                ]);
            });
            DB::commit();
        } catch (\Exception $err)
        {
            DB::rollBack();
            throw $err;
        }


    }
}
