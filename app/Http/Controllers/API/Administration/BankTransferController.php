<?php

namespace App\Http\Controllers\API\Administration;

use App\Events\AddSubscriptionEvent;
use App\Events\AddTransactionEvent;
use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Mail\RejectedPaymentMail;
use App\Models\Administration\Plan;
use App\Models\Administration\Transaction;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BankTransferController extends Controller
{

    public function payment(Request $request)
    {
        DB::beginTransaction();

        try {
            $status_type = StatusType::where('name', 'transaction')->first();
            $status = Status::where('status_type_id', $status_type->id)->where('name', 'pending')->first();

            $status_type = StatusType::where('name', 'plan')->first();
            $approved_plan_status = Status::where('status_type_id', $status_type->id)->where('name', 'pending')->first();

            $user_id = Auth::user()->id;

            $plan_id = $request->plan_id;

            $user = User::find($user_id);
            $plan = Plan::find($plan_id);
            $transaction = Transaction::where('user_id', $user_id)->latest()->first();

            AddTransactionEvent::dispatch($user_id, $plan_id, $status->id, "bank_transfer", $approved_plan_status->id, json_encode($request->all()), $request->reference);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Exito']);
        } catch (\Exception $e) {
            \Log::info($e);

            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Hubo un error']);

        }

    }

    public function handle_transaction(Request $request)
    {

        $status_type = StatusType::where('name', 'plan')->first();
        $approved_plan_status = Status::where('status_type_id', $status_type->id)->where('name', $request->status)->first();
        $plan = Plan::find($request->plan_id);

        $transaction = Transaction::find($request->transaction_id);
        $user = User::find($transaction->user_id);

        $transaction->approved_plan_status_id = $approved_plan_status->id;
        $transaction->save();

        switch ($request->status) {
            case 'active':
                AddSubscriptionEvent::dispatch($user->id, $request->plan_id);
                $transaction_status_type = StatusType::where('name', 'transaction')->first();
                $transaction_plan_status = Status::where('status_type_id', $transaction_status_type->id)->where('name', 'completed')->first();

                $transaction->approved_plan_status_id = $approved_plan_status->id;
                $transaction->status_id = $transaction_plan_status->id;
                $transaction->save();

                if (!str_contains($user->email, '@mail.com')) {

                    Mail::to($user->email)->send(new InvoiceMail($user, $plan, $transaction));
                }

                break;

            case 'inactive':

                $transaction_status_type = StatusType::where('name', 'transaction')->first();
                $transaction_plan_status = Status::where('status_type_id', $transaction_status_type->id)->where('name', 'rejected')->first();
                $approved_plan_status = Status::where('status_type_id', $status_type->id)->where('name', "canceled")->first();

                $transaction->approved_plan_status_id = $approved_plan_status->id;
                $transaction->status_id = $transaction_plan_status->id;
                $transaction->save();

                if (!str_contains($user->email, '@mail.com')) {

                    Mail::to($user->email)->send(new RejectedPaymentMail($user, $plan, $transaction));
                }
                break;

        }

        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
