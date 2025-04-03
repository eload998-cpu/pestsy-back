<?php

namespace Database\Seeders;

use App\Models\Administration\Plan;
use Illuminate\Database\Seeder;
use App\Models\Status;
use App\Models\StatusType;


class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::truncate();

        $status_type = StatusType::where('name', 'plan')->first();
        $status = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

        Plan::create(
            [
                "name" => "Gratis",
                "period_type" => "weekly",
                "period" => 1,
                "order_quantity" => 5,
                "status_id" => $status->id,
            ]
        );

        Plan::create(
            [
                "name" => "Fumigador",
                "period_type" => "monthly",
                "period" => 1,
                "order_quantity" => 300,
                "status_id" => $status->id,
                "price"=>7
            ]
        );

        Plan::create(
            [
                "name" => "Premium",
                "period_type" => "monthly",
                "period" => 1,
                "order_quantity" => 800,
                "status_id" => $status->id,
                "price"=>10
            ]
        );

    }
}
