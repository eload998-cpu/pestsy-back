<?php

namespace Database\Seeders;

use App\Models\Administration\Plan;
use Illuminate\Database\Seeder;

class PlanPaypalIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        updateConnectionSchema("administration");

        Plan::where("name", "like", "%Fumigador%")->update(["paypal_id" => config('app.paypal_standard_plan_id')]);
        Plan::where("name", "like", "%Premium%")->update(["paypal_id" => config('app.paypal_premium_plan_id')]);

    }
}
