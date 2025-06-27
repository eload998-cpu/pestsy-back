<?php
namespace App\Console\Commands;

use App\Events\AddRoleEvent;
use App\Models\Administration\Company;
use App\Models\Administration\Plan;
use App\Models\Administration\UserSubscription;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use App\Services\PaypalService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users about theirs subscription states';

    /**
     * Execute the console command.
     *
     * @return int
     */

    private $paypalService;
    private $userService;

    public function __construct(PaypalService $paypalService, UserService $userService)
    {

        parent::__construct();
        $this->paypalService = $paypalService;
        $this->userService   = $userService;

    }

    public function handle()
    {
        $users = User::all();

        $user_status_type = StatusType::where('name', 'plan')->first();
        $user_status      = Status::where('status_type_id', $user_status_type->id)->where('name', 'inactive')->first();

        foreach ($users as $user) {

            $subscription = UserSubscription::where('user_id', $user->id)->latest()->first();

            if ($subscription) {
                $today       = Carbon::parse(Carbon::now());
                $expire_date = Carbon::parse($subscription->end_date);

                if ($today->gte($expire_date)) {

                    $user           = User::find($user->id);
                    $subscriptionId = $user->paypal_subscription_id;

                    if (! empty($subscriptionId)) {
                        $accessToken = $this->paypalService->getPayPalToken();
                        $response    = $this->paypalService->cancelSubscription($subscriptionId, $accessToken, "Account with no funds");

                    }

                    $this->userService->inactivateUser($user->id);
                    $sub            = UserSubscription::find($subscription->id);
                    $sub->status_id = $user_status->id;
                    $sub->save();

                    if ($user->is_owner) {

                        $now  = Carbon::now();
                        $plan = Plan::where('name', 'Fumigador')->first();
                        AddRoleEvent::dispatch($user->id, 'fumigator');
                        $plan_status_type     = StatusType::where('name', 'plan')->first();
                        $plan_status          = Status::where('status_type_id', $plan_status_type->id)->where('name', 'active')->first();
                        $new_user_status_type = StatusType::where('name', 'user')->first();
                        $new_user_status      = Status::where('status_type_id', $new_user_status_type->id)->where('name', 'active')->first();

                        $user->subscriptions()->attach([$plan->id => ['start_date' => $now, 'end_date' => Carbon::parse($now)->addMonths(1), 'status_id' => $plan_status->id, 'created_at' => $now]]);
                        $user->status_id = $new_user_status->id;
                        $user->save();
                        $company                 = Company::find($user->company->id);
                        $company->order_quantity = $plan->order_quantity;
                        $company->save();
                    } else {

                        $new_user_status_type = StatusType::where('name', 'user')->first();
                        $new_user_status      = Status::where('status_type_id', $new_user_status_type->id)->where('name', 'inoperative')->first();
                        $user->status_id      = $new_user_status->id;
                        $user->save();

                    }

                }
            }

        }
        return Command::SUCCESS;
    }
}
