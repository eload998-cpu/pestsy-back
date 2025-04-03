<?php
namespace Database\Factories\Module;

use App\Models\Module\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Client::class;
    private static $lastCreatedAt;


    public function definition()
    {
        $module_name = "module_12";
        updateConnectionSchema($module_name);

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
