<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmailCCFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email_cc' => $this->faker->unique()->safeEmail(),
            'isUse' => $this->faker->boolean()
        ];
    }
}
