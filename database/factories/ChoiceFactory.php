<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Choice>
 */
class ChoiceFactory extends Factory
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
            'point' => rand(1,100)
        ];
    }
}
