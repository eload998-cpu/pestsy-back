<?php
namespace App\Console\Commands;

use App\Mail\ExpiratedAccountEmail;
use App\Mail\WarningExpirationEmail;
use App\Models\Administration\Company;
use App\Models\Administration\UserSubscription;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendExpirationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails before expiration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::where('is_owner', true)->get();

        $status_type = StatusType::where('name', 'plan')->first();
        $status      = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

        foreach ($users as $user) {

            $subscription = UserSubscription::where('user_id', $user->id)->latest()->first();

            \Log::info($subscription);

            $today       = Carbon::parse(Carbon::now());
            $exp_date    = Carbon::parse($subscription->end_date);
            $expire_date = Carbon::parse($subscription->end_date);
            $validation  = $expire_date->subDays(3);

            if ($today->gte($validation) && $subscription->status_id == $status->id && $subscription->plan->name == "Premium") {

                if ($today->gte($exp_date)) {

                    $status = Status::where('status_type_id', $status_type->id)->where('name', 'inactive')->first();

                    $sub = UserSubscription::find($subscription->id);

                    $company                 = Company::find($user->company->id);
                    $company->order_quantity = 0;
                    $company->save();

                    $sub->status_id = $status->id;
                    $sub->save();

                    Mail::to($user->email)->send(new ExpiratedAccountEmail($user));

                } else {
                    if (! $user->active_subscription) {
                        Mail::to($user->email)->send(new WarningExpirationEmail($user, $subscription->end_date));
                    }
                }

            }

        }
        return Command::SUCCESS;
    }
}
