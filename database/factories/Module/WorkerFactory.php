<?php
namespace Database\Factories\Module;

use App\Models\Module\Worker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class WorkerFactory extends Factory
{
    protected $model = Worker::class;
    private static $lastCreatedAt;

    public function definition()
    {
        updateConnectionSchema("modules");

        if (is_null(self::$lastCreatedAt)) {
            self::$lastCreatedAt = Carbon::parse('2024-11-01');
        } else {
            self::$lastCreatedAt->addDay();
        }

        return [
            'first_name'            => fake()->firstName,
            'last_name'             => fake()->lastName,
            'identification_number' => fake()->postcode,
            'email'                 => fake()->unique()->safeEmail(),
            'cellphone'             => fake()->phoneNumber,
            'direction'             => fake()->address,
            'created_at'            => self::$lastCreatedAt->copy(),
        ];
    }
}
