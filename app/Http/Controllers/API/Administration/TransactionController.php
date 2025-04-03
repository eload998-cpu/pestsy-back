<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Models\Administration\Plan;
use App\Models\Administration\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    private $transaction;
    private $paginate_size = 6;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            $transactions = $this
                ->transaction
                ->join("administration.plans as plans", "administration.transactions.plan_id", "=", "plans.id")
                ->join("public.statuses as statuses", "administration.transactions.status_id", "=", "statuses.id")
                ->join("public.statuses as approved_status", "administration.transactions.approved_plan_status_id", "=", "approved_status.id")
                ->select("administration.transactions.id", "administration.transactions.data", "administration.transactions.plan_id", "administration.transactions.bill_code",
                    "statuses.name as status_name", "plans.name as plan_name", "plans.price as plan_price",
                    DB::raw("to_char(administration.transactions.created_at, 'YYYY-MM-DD') as date"),
                    "administration.transactions.type",
                    "approved_status.name as approved_status_name");

            if ($request->search) {
                $search_value = $request->search;
                $transactions = $transactions->whereRaw("LOWER(transactions.bill_code) ILIKE '%{$search_value}%'");

            }

            switch ($request->type) {
                case 'non-national':
                    $transactions = $transactions->where("type", "!=", "bank_transfer");

                    break;

                case 'national':
                    $transactions = $transactions->where("type", "=", "bank_transfer");
                    break;
            }

            $transactions = $transactions->orderBy("transactions.created_at", "desc");

            $transactions = $transactions->paginate($this->paginate_size);

            if ($request->type == "national") {

                foreach ($transactions as $key => &$value) {

                    $planPrice                = Plan::find($value->plan_id)->price;
                    $extra                    = json_decode($value->data, true);
                    $value->transactionAmount = $extra["extra"]["price"];
                    $value->reference         = $extra["reference"];
                    $value->planPrice         = $planPrice;

                }
            }
            $transactions = parsePaginator($transactions);

            return response()->json($transactions);
        } catch (\Exception $e) {
            \Log::info($e);
            throw $e;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::find($id);

        return response()->json(['success' => true, 'data' => $transaction]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::destroy($id);
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
