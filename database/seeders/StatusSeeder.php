<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\StatusType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusType::truncate();
        Status::truncate();

        $status_type = ["user", "order", "plan", "transaction", "ticket"];

        $user_status = ["active", "inactive","inoperative"];
        $plan_status = ["active", "inactive", "pending", "canceled"];
        $order_status = ["in process", "pending", "completed", "discarded"];
        $ticket_status = ["pending", "closed", "discarded"];
        $bank_transfer_status = ["pending", "completed", "rejected"];

        foreach ($status_type as $key => $stp) {
            $st = StatusType::create([
                "name" => $stp,
            ]);

            switch ($stp) {
                case 'user':
                    foreach ($user_status as $key => $value) {
                        Status::create(
                            [
                                "name" => $value,
                                "status_type_id" => $st->id,
                            ]
                        );
                    }
                    break;

                case 'order':
                    foreach ($order_status as $key => $value) {
                        Status::create(
                            [
                                "name" => $value,
                                "status_type_id" => $st->id,
                            ]
                        );
                    }
                    break;

                case 'plan':
                    foreach ($plan_status as $key => $value) {
                        Status::create(
                            [
                                "name" => $value,
                                "status_type_id" => $st->id,
                            ]
                        );
                    }
                    break;

                case 'transaction':
                    foreach ($bank_transfer_status as $key => $value) {
                        Status::create(
                            [
                                "name" => $value,
                                "status_type_id" => $st->id,
                            ]
                        );
                    }
                    break;

                case 'ticket':
                    foreach ($ticket_status as $key => $value) {
                        Status::create(
                            [
                                "name" => $value,
                                "status_type_id" => $st->id,
                            ]
                        );
                    }
                    break;

            }

        }

    }
}
