<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{

    public function definition()
    {
        return [
            'description'   => $this->faker->unique->text(20),
            'value'         => rand(100, 999),
            'date'          => $this->faker->unique->date('Y-m-d', date('Y-m-d', strtotime('2022-12-30')))
        ];
    }
}
