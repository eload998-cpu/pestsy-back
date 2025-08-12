<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            //CountryLocationSeeder::class,
            //StatusSeeder::class,
            //PlanSeeder::class,
            //RoleSeeder::class,
            //PermissionSeeder::class,
            //PlanPaypalIdSeeder::class,
            //ClientSeeder::class,
            //WorkerSeeder::class
            MasterSeeder::class,
            //CorrectiveActionSeeder::class,

        ]);

        //\App\Models\Module\Client::factory(10)->create();
        //\App\Models\Module\Worker::factory(10)->create();

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
