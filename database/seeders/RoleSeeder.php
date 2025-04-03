<?php

namespace Database\Seeders;

use App\Models\Role;

use App\Models\User;
use App\Models\Administration\Plan;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Status;
use App\Models\StatusType;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        updateConnectionSchema('administration');

        Role::truncate();
        $user = User::where('email', 'felcast999@gmail.com')->first();

        $roles =
            [
            "system user",
            "operator",
            "administrator",
            "fumigator",
            "super administrator",
        ];

        foreach ($roles as $key => $value) {
            Role::create(
                [
                    "name" => str_replace(" ", "_", $value),
                    "display_name" => $value,
                ]);
        }

        $role = Role::where('name', 'super_administrator')->first();
        $user->roles()->sync([$role->id]);

        $plan = Plan::where('name', 'Premium')->first();
        $now =Carbon::now();

        $status_type = StatusType::where('name', 'plan')->first();
        $status = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

        $user->subscriptions()->sync([$plan->id => ['start_date' => $now, 'end_date' => Carbon::parse($now)->addMonths(5),'status_id' => $status->id]]);
    }
}
