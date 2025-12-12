<?php
namespace App\Http\Controllers\API\Administration;

use App\Events\AddSubscriptionEvent;
use App\Events\AddTransactionEvent;
use App\Http\Controllers\Controller;
use App\Mail\FailedPaymentEmail;
use App\Models\Administration\Plan;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use App\Services\PaypalService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{

    private $paypalService;
    private $userService;

    public function __construct(PaypalService $paypalService, UserService $userService)
    {
        $this->paypalService = $paypalService;
        $this->userService   = $userService;

    }

    public function handleWebHook(Request $request)
    {
        Log::info('Webhook received:', $request->all());

        $headers = $request->headers->all();
        $payload = $request->getContent();

        if ($this->verifyWebhook($headers, $payload)) {
            $event = $request->input('event_type');

            switch ($event) {

                case 'BILLING.SUBSCRIPTION.UPDATED':

                    $this->updateSubscription($request);
                    break;

                case 'BILLING.SUBSCRIPTION.EXPIRED':
                    break;

                case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED':

                    try {

                        $plan = Plan::where("paypal_id", $request["resource"]["plan_id"])->first();

                        $lastFailedPayment = Carbon::parse($request["resource"]["billing_info"]["last_failed_payment"]["time"])->format('d/m/Y');
                        Mail::to($request["resource"]["subscriber"]["email_address"])
                            ->send(new FailedPaymentEmail($request["resource"], $request["id"], $plan->name, $lastFailedPayment));
                    } catch (\Exception $e) {
                        \Log::error($e);
                    }

                    break;

                case 'BILLING.SUBSCRIPTION.CANCELLED':

                    $this->hookCancelSubscription($request);
                    break;

                case 'PAYMENT.SALE.COMPLETED':
                    $this->renewSubscription($request);

                    break;

            }

        } else {
            Log::warning('Invalid webhook signature');
            return response('Unauthorized', 401);
        }

        return response('Webhook handled', 200);
    }

    private function verifyWebhook($headers, $payload)
    {
        try {
            $transmissionId   = $headers['paypal-transmission-id'][0];
            $transmissionTime = $headers['paypal-transmission-time'][0];
            $certUrl          = $headers['paypal-cert-url'][0];
            $authAlgo         = $headers['paypal-auth-algo'][0];
            $transmissionSig  = $headers['paypal-transmission-sig'][0];

            $webhookId = config('services.paypal.webhook_id');

            $verifyPayload = [
                'transmission_id'   => $transmissionId,
                'transmission_time' => $transmissionTime,
                'cert_url'          => $certUrl,
                'auth_algo'         => $authAlgo,
                'transmission_sig'  => $transmissionSig,
                'webhook_id'        => $webhookId,
                'webhook_event'     => json_decode($payload, true),
            ];

            $accessToken = $this->paypalService->getPayPalToken();

            $response = $this->paypalService->verifyWebhook($accessToken, $verifyPayload);

            if ($response->successful() && $response->json()['verification_status'] === 'SUCCESS') {
                return true;
            }

            Log::warning('Webhook verification failed:', $response->json());
            return false;

        } catch (\Exception $e) {
            Log::error('Webhook verification error:', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function getSubscriptionDetail(Request $request)
    {

        try {
            $accessToken = $this->paypalService->getPayPalToken();

            $response = $this->paypalService->subscriptionDetail($request->get('id'), $accessToken);
            if ($response->successful()) {
                $subscription = $response->json();
                $data         = [];

                if (isset($subscription['name']) && $subscription['name'] == 'RESOURCE_NOT_FOUND') {
                    $user                             = User::where('paypal_subscription_id', $request->get('id'))->first();
                    $user->verify_paypal_subscription = false;
                    $user->save();
                    return response()->json(["success" => true, "expired" => true]);
                }

                if ($subscription['status'] == 'ACTIVE') {

                    $data["resource"]["billing_agreement_id"] = $request->get('id');
                    $request                                  = new Request($data);

                    $this->renewSubscription($request);

                    return response()->json(["success" => true, "expired" => true]);
                } else {
                    $subscriptionCreatedTime = Carbon::parse($subscription["create_time"]);
                    $now                     = Carbon::now();

                    if ($now->diffInMinutes($subscriptionCreatedTime) > 3) {

                        $user                             = User::where('paypal_subscription_id', $request->get('id'))->first();
                        $user->verify_paypal_subscription = false;
                        $user->save();
                        return response()->json(["success" => false, "expired" => true]);

                    }

                    return response()->json(["success" => false, "expired" => false]);

                }

            } else {

                $user                             = User::where('paypal_subscription_id', $request->get('id'))->first();
                $user->verify_paypal_subscription = false;
                $user->save();
                return response()->json(["success" => true, "expired" => true]);

            }

        } catch (\Exception $e) {
            \Log::error($e);
            $user                             = User::where('paypal_subscription_id', $request->get('id'))->first();
            $user->verify_paypal_subscription = false;
            $user->save();
            return response()->json(["success" => true, "expired" => true]);
        }

    }

    public function updateSubscription(Request $request)
    {

        try {

            $data = $request->all();

            $subscriptionId = $data["resource"]["id"];

            //UPDATER USER'S SUBSCRIPTION IF NOT ACTIVE

            $user = User::where('paypal_subscription_id', $subscriptionId)->first();

            if ($user) {

                $status_type = StatusType::where('name', 'transaction')->first();
                $status      = Status::where('status_type_id', $status_type->id)->where('name', 'completed')->first();

                $status_type          = StatusType::where('name', 'plan')->first();
                $approved_plan_status = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

                $planId = Cache::get($subscriptionId);

                $user->verify_paypal_subscription = false;
                $user->save();

                AddSubscriptionEvent::dispatch($user->id, $planId, true);
                AddTransactionEvent::dispatch($user->id, $planId, $status->id, "paypal", $approved_plan_status->id, json_encode($request->all()));

            }

        } catch (\Exception $e) {
            \Log::error($e);
        }

    }

    public function hookCancelSubscription(Request $request)
    {

        try {

            $data = $request->all();

            $subscriptionId = $data["resource"]["id"];

            $accessToken = $this->paypalService->getPayPalToken();
            $response    = $this->paypalService->cancelSubscription($subscriptionId, $accessToken, $request->reason);

            $user = User::where('paypal_subscription_id', $subscriptionId)->first();

            if ($user) {

                $this->userService->inactivateUser($user->id);

            }

        } catch (\Exception $e) {
            \Log::error($e);
        }

    }

    public function renewSubscription(Request $request)
    {

        try {

            $data = $request->all();

            $subscriptionId = $data["resource"]["billing_agreement_id"];

            //UPDATER USER'S SUBSCRIPTION IF NOT ACTIVE

            $user = User::where('paypal_subscription_id', $subscriptionId)->first();

            if ($user && ! $user->active_subscription) {

                $status_type = StatusType::where('name', 'transaction')->first();
                $status      = Status::where('status_type_id', $status_type->id)->where('name', 'completed')->first();

                $status_type          = StatusType::where('name', 'plan')->first();
                $approved_plan_status = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

                $user->active_subscription        = true;
                $user->verify_paypal_subscription = false;
                $user->save();
                $planId = Cache::get($subscriptionId);

                AddSubscriptionEvent::dispatch($user->id, $planId, true);
                AddTransactionEvent::dispatch($user->id, $planId, $status->id, "paypal", $approved_plan_status->id, json_encode($request->all()));

            }

        } catch (\Exception $e) {
            \Log::error($e);
        }

    }

    public function createSubscription(Request $request)
    {

        try {
            $response = $request->all();

            //CREATE SUBSCRIPTION HERE

            $status_type = StatusType::where('name', 'transaction')->first();
            $status      = Status::where('status_type_id', $status_type->id)->where('name', 'completed')->first();

            $status_type          = StatusType::where('name', 'plan')->first();
            $approved_plan_status = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

            //UPDATER USER'S SUBSCRIPTION IF NOT ACTIVE

            $user = User::find(Auth::user()->id);

            $user->paypal_subscription_id = $response["data"]["subscriptionID"];
            $user->active_subscription    = true;
            $user->save();

            Cache::put($response["data"]["subscriptionID"], $request->plan_id, now()->addMinutes(60));

            AddSubscriptionEvent::dispatch(Auth::user()->id, $request->plan_id, true);
            AddTransactionEvent::dispatch(Auth::user()->id, $request->plan_id, $status->id, "paypal", $approved_plan_status->id, json_encode($response["data"]));

            return response()->json(['success' => true, 'message' => 'Exito'], 200);
        } catch (\Exception $e) {
            \Log::error($e);
        }

    }

    public function paymentSuccessV2(Request $request)
    {

        try {
            $response = $request->all();

            if (isset($response["data"]["subscriptionID"])) {

                return $this->createSubscription($request);
            }

            return response()->json(['success' => false, 'message' => 'Error'], 422);
        } catch (\Exception $e) {
            \Log::error($e);
        }

    }

    public function changePlan(Request $request)
    {

        try {

            $token    = $this->paypalService->getPayPalToken();
            $response = $request->all();

            $plan     = Plan::find($request->plan_id);
            $isMobile = $request->isMobile;

            $updateSubscription = $this->paypalService->updateSubscription($token, Auth::user()->paypal_subscription_id, $plan->paypal_id, $isMobile);
            if ($updateSubscription["success"]) {
                Cache::put(Auth::user()->paypal_subscription_id, $plan->id, now()->addMinutes(60));

                $user                             = User::find(Auth::user()->id);
                $user->verify_paypal_subscription = true;
                $user->save();

                return response()->json(['success' => true, 'message' => 'Exito', 'data' => $updateSubscription["response"]], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Hubo un error', 'data' => $updateSubscription["details"]], 200);

            }

        } catch (\Exception $e) {
            Log::error($e);
        }

    }

    //Method used once the subscription is successfully changed
    public function saveTransactionData(Request $request)
    {

        try
        {
            $status_type = StatusType::where('name', 'transaction')->first();
            $status      = Status::where('status_type_id', $status_type->id)->where('name', 'completed')->first();

            $status_type          = StatusType::where('name', 'plan')->first();
            $approved_plan_status = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();
            $plan                 = Plan::where('paypal_id', $request["resource"]["plan_id"])->first();
            $user                 = User::where('paypal_subscription_id', $request["resource"]["id"])->first();
            $planId               = empty($plan) ? Cache::get($user->paypal_subscription_id) : $plan->id;
            $data                 = $request->all();
            AddTransactionEvent::dispatch($user->id, $planId, $status->id, "paypal", $approved_plan_status->id, json_encode($data), false);

        } catch (\Exception $e) {
            \Log::error($e);
        }

    }

    public function changeSuccessful(Request $request)
    {

        $accessToken = $this->paypalService->getPayPalToken();

        $subscription = $this->validateSubscription($request->get('subscription_id'), $accessToken);
        if (! $subscription) {
            abort(403, 'Invalid subscription ID.');
        }

        return redirect(config('app.front_app_base_url') . '/transaccion-exitosa');

    }

    public function changeUnSuccessful(Request $request)
    {

        //CANCEL SUBSCRITION HERE IF NEEDED
        return redirect(config('app.front_app_base_url') . '/transaccion-no-completada');

    }

    public function validateSubscription($subscriptionId, $accessToken, $status = 'ACTIVE')
    {
        $response = $this->paypalService->subscriptionDetail($subscriptionId, $accessToken);
        if ($response->successful()) {
            $subscription = $response->json();

            // Verify key details in the subscription
            if ($subscription['status'] === $status) {
                return true;
            }
        }

        return false;
    }

    public function cancelSubscription(Request $request)
    {
        try {

            $subscriptionId = Auth::user()->paypal_subscription_id;
            $accessToken    = $this->paypalService->getPayPalToken();
            $response       = $this->paypalService->cancelSubscription($subscriptionId, $accessToken, $request->reason);
            $this->userService->inactivateUser(Auth::user()->id);
            return response()->json(['success' => true, 'message' => 'Exito', 'data' => []], 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['success' => false, 'message' => 'Error', 'data' => []], 500);

        }

    }

    public function createOrder($plan)
    {
        $accessToken = $this->paypalService->getPayPalToken();

        $response = $this->paypalService->createOrder($accessToken, $plan);

        return $response;
    }

    public function createOrderForMobile(Request $request)
    {
        try {
            $provider = new PayPalClient();
            $user     = User::find(Auth::user()->id);
            $plan     = Plan::find($request->id);

            $status_type = StatusType::where('name', 'transaction')->first();
            $status      = Status::where('status_type_id', $status_type->id)->where('name', 'completed')->first();

            $status_type          = StatusType::where('name', 'plan')->first();
            $approved_plan_status = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

            $accessToken = $this->paypalService->getPayPalToken();

            //CREATE THE SUBSCRIPTION HERE

            $subscriptionResponse = $this->paypalService->createOrder($accessToken, $plan);
            if ($subscriptionResponse["id"]) {

                AddSubscriptionEvent::dispatch($user->id, $plan->id);
                AddTransactionEvent::dispatch($user->id, $plan->id, $status->id, "paypal", $approved_plan_status->id, json_encode($subscriptionResponse));

                return response()->json(['success' => true, 'message' => 'Subscription created successfully', 'data' => []], 200);

            }
            return response()->json(['success' => false, 'message' => 'Error', 'data' => []], 500);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['success' => false, 'message' => 'Error capturing the order.'], 500);
        }

    }

    public function createSubscriptionForMobile(Request $request)
    {
        try {
            $provider = new PayPalClient();
            $user     = User::find(Auth::user()->id);
            $plan     = Plan::find($request->id);

            $token = $provider->getAccessToken();

            //CREATE THE SUBSCRIPTION HERE

            $subscriptionResponse = $provider->addProductById($plan->paypal_product_id)
                ->addBillingPlanById($plan->paypal_id)
                ->setReturnAndCancelUrl(config('app.front_app_base_url') . '/home/suscripciones/pago/' . $plan->id . '?mobile_status=true', config('app.front_app_base_url') . '/home/tablero/' . $plan->id . '?mobile_status=false')
                ->setupSubscription($user->full_name, $user->email, Carbon::now()->addMinutes(1)->toISOString());
            $user->paypal_subscription_id     = $subscriptionResponse["id"];
            $user->verify_paypal_subscription = true;

            $user->save();

            Cache::put($subscriptionResponse["id"], $plan->id, now()->addMinutes(60));

            foreach ($subscriptionResponse['links'] as $link) {
                if ($link['rel'] === 'approve') {

                    $user->save();

                    return response()->json(['url' => $link["href"]], 200);
                    break;
                }
            }

            return response()->json(['success' => false, 'message' => 'Error', 'data' => []], 500);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['success' => false, 'message' => 'Error capturing the order.'], 500);
        }

    }

}
