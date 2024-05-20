<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\JurusanSmk;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            JurusanSmkSeeder::class,
            JurusanPnlSeeder::class,
            UserSeeder::class,
            CriteriaSeeder::class,
            CriteriaJurusanSeeder::class,
            QuestionTitleSeeder::class,
//            QuestionSeeder::class,
            SubCriteriaSeeder::class,
            SubjectSeeder::class,
//            BobotCreteriaSeeder::class,
//            BobotSubjectSeeder::class
        ]);
    }
}
