<?php

namespace Database\Factories\Module;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        updateConnectionSchema("modules");

        return [
            'common_name' => fake()->name(),
            'scientific_name' => fake()->name()
        ];
    }


  
}
