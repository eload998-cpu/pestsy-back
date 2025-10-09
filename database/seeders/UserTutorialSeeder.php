<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\UserTutorial;
use Illuminate\Database\Seeder;

class UserTutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserTutorial::truncate();
        $users = User::all();
        foreach ($users as $user) {
            UserTutorial::create([
                "user_id"         => $user->id,
                "client_tutorial" => false,
                "worker_tutorial" => false,
            ]);
        }
    }
}
