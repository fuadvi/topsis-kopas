<?php

namespace Database\Factories;

use App\Models\SubCriteria;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SubCriteriaFactory extends Factory
{
    protected $model = SubCriteria::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
        ];
    }
}
