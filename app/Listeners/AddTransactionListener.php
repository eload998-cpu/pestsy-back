<?php
namespace App\Listeners;

use App\Events\AddTransactionEvent;
use App\Mail\InHoldInvoiceMail;
use App\Mail\InvoiceMail;
use App\Models\Administration\BankTransfer;
use App\Models\Administration\Plan;
use App\Models\Administration\Transaction;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AddTransactionListener
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
     * @param  \App\Events\AddTransactionEvent  $event
     * @return void
     */
    public function handle(AddTransactionEvent $event)
    {
        DB::beginTransaction();

        try
        {

            $data   = json_decode($event->data, true);
            $user   = User::find($event->user_id);
            $plan   = Plan::find($event->plan_id);
            $now    = Carbon::now();
            $status = Status::find($event->status_id);

            $paypalBillCode = ($event->type == "paypal") ? (isset($data["orderID"]) ? $data["orderID"] : (isset($data["id"]) ? $data["id"] : generate_bill_code())) : generate_bill_code();

            $bill_code = ($event->type == "bank_transfer" || $event->type == "zinli") ? $event->reference : $paypalBillCode;

            $transaction = Transaction::create([
                "user_id"                 => $user->id,
                "plan_id"                 => $plan->id,
                "status_id"               => $status->id,
                "bill_code"               => $bill_code,
                "type"                    => $event->type,
                "approved_plan_status_id" => $event->plan_status_id,
                "data"                    => $event->data,
            ]);

            $transaction = Transaction::find($transaction->id);

            if ($event->type == "bank_transfer" || $event->type == "zinli") {
                $bank_transfer = BankTransfer::create([
                    "reference"      => $event->reference,
                    "user_id"        => $user->id,
                    "status_id"      => $status->id,
                    "transaction_id" => $transaction->id,
                ]);

                if ($event->sentEmail) {
                    Mail::to([$user->email, 'felcast999@gmail.com'])->send(new InHoldInvoiceMail($user, $plan, $transaction));

                }

            } else {
                if (! str_contains($user->email, '@mail.com')) {

                    if ($event->sentEmail) {

                        Mail::to([$user->email, 'felcast999@gmail.com'])->send(new InvoiceMail($user, $plan, $transaction));

                    }
                }
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Exception occurred: ' . $e->getMessage());

        }
    }
}
