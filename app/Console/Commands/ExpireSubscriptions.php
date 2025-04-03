<?php
namespace App\Console\Commands;

use App\Models\Administration\Company;
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

        $status_type = StatusType::where('name', 'plan')->first();
        $status      = Status::where('status_type_id', $status_type->id)->where('name', 'inactive')->first();

        foreach ($users as $user) {

            $subscription = UserSubscription::where('user_id', $user->id)->latest()->first();

            $today       = Carbon::parse(Carbon::now());
            $expire_date = Carbon::parse($subscription->end_date);

            if ($today->gte($expire_date)) {

                $user           = User::find($user->id);
                $subscriptionId = $user->paypal_subscription_id;

                if (! empty($subscriptionId)) {
                    $accessToken = $this->paypalService->getPayPalToken();
                    $response    = $this->paypalService->cancelSubscription($subscriptionId, $accessToken,"Account with no funds");

                }

                $this->userService->inactivateUser($user->id);

                $sub = UserSubscription::find($subscription->id);

                $company                 = Company::find($user->company->id);
                $company->order_quantity = 0;
                $company->save();

                $sub->status_id = $status->id;
                $sub->save();
            }

        }
        return Command::SUCCESS;
    }
}
