<?php

namespace Database\Factories;

use App\Models\QuestionTitle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->boolean;
        return [
            'name' => $type == 0 ? $this->faker->text(20) : $this->faker->imageUrl,
            'type' =>$type,
            'question_title_id' => QuestionTitle::whereNot('id',3)->get()->random()->id
        ];
    }
}
