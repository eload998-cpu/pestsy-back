<?php
namespace App\Listeners;

use App\Events\AddSubscriptionEvent;
use App\Models\Administration\Company;
use App\Models\Administration\Plan;
use App\Models\Administration\UserSubscription;
use App\Models\Module\Operator;
use App\Models\Module\SystemUser;
use App\Models\Role;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddSubscriptionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AddSubscriptionEvent  $event
     * @return void
     */
    public function handle(AddSubscriptionEvent $event)
    {
        DB::beginTransaction();

        try
        {

            $user = User::find($event->user_id);
            updateConnectionSchema("modules");

            $operators    = Operator::where("company_id", $user->company_id)->get();
            $system_users = SystemUser::where("company_id", $user->company_id)->get();

            updateConnectionSchema("administration");

            $plan = Plan::find($event->plan_id);
            $now  = Carbon::now();

            $plan_status_type    = StatusType::where('name', 'plan')->first();
            $plan_status         = Status::where('status_type_id', $plan_status_type->id)->where('name', 'active')->first();
            $plan_pending_status = Status::where('status_type_id', $plan_status_type->id)->where('name', 'inactive')->first();

            UserSubscription::where('user_id', $user->id)->where('status_id', $plan_status->id)->update(["user_subscriptions.status_id" => $plan_pending_status->id]);

            foreach ($operators as $key => $value) {
                UserSubscription::where('user_id', $value->user_id)->where('status_id', $plan_status->id)->update(["user_subscriptions.status_id" => $plan_pending_status->id]);
            }

            foreach ($system_users as $key => $value) {
                UserSubscription::where('user_id', $value->user_id)->where('status_id', $plan_status->id)->update(["user_subscriptions.status_id" => $plan_pending_status->id]);
            }

            $expire_date = Carbon::parse($now)->addMonths($plan->period);
            $expire_date = ($event->is_subscription) ? Carbon::parse($expire_date)->addDays(3) : $expire_date;
            $user->subscriptions()->attach([$plan->id => ['start_date' => $now, 'end_date' => $expire_date, 'status_id' => $plan_status->id, 'created_at' => $now]]);

            $company                 = Company::find($user->company->id);
            $company->order_quantity = $plan->order_quantity;
            $company->save();

            $status_type = StatusType::where('name', 'user')->first();
            $status      = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();
            foreach ($operators as $key => $value) {
                $op            = User::find($value->user_id);
                $op->status_id = $status->id;
                $op->save();
                $op->subscriptions()->attach([$plan->id => ['start_date' => $now, 'end_date' => $expire_date, 'status_id' => $plan_status->id, 'created_at' => $now]]);

            }

            foreach ($system_users as $key => $value) {
                $su            = User::find($value->user_id);
                $su->status_id = $status->id;
                $su->save();
                $su->subscriptions()->attach([$plan->id => ['start_date' => $now, 'end_date' => $expire_date, 'status_id' => $plan_status->id, 'created_at' => $now]]);

            }

            switch ($plan->name) {
                case 'Fumigador':
                    $role = Role::where('name', "fumigator")->first();

                    $user->roles()->detach();
                    $user->roles()->attach($role->id);
                    break;

                case 'Premium':
                    $role = Role::where('name', "administrator")->first();

                    $user->roles()->detach();
                    $user->roles()->attach($role->id);
                    break;
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Exception occurred: ' . $e->getMessage());

        }
    }
}
