<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaypalService
{

    private $paypalUrl;
    private $mode;

    public function __construct()
    {

        $this->mode            = config('paypal.mode');
        $this->paypalUrl = config("paypal.{$this->mode}.endpoint_url");
    }

    public function getPayPalToken()
    {
        try {

            $clientId     = config("paypal.{$this->mode}.client_id");
            $clientSecret = config("paypal.{$this->mode}.client_secret");

            $response = Http::withBasicAuth($clientId, $clientSecret)
                ->asForm()
                ->post("{$this->paypalUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {

                $accessToken = $response->json()['access_token'];
                return $accessToken;
            } else {
                return response()->json(null);
            }
        } catch (\Exception $e) {
            Log::info($e);
        }

    }

    public function updateSubscription($accessToken, $subscriptionId, $planId, $isMobile = false)
    {
        try {

            $data = [
                'plan_id'             => $planId,
                'application_context' => [
                    'payment_method' => [
                        'payer_selected'  => 'PAYPAL',
                        'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',

                    ],
                ],
            ];

            if ($isMobile) {
                $data["application_context"]["return_url"] = config('app.front_app_base_url') . '/home/tablero/' . $planId . '?mobile_status=true';
                $data["application_context"]["cancel_url"] = config('app.front_app_base_url') . '/home/tablero/' . $planId . '?mobile_status=false';

            } else {
                $data["application_context"]["return_url"] = config('app.backend_app_base_url') . '/api/paypal/change-successful';
                $data["application_context"]["cancel_url"] = config('app.backend_app_base_url') . '/api/paypal/change-unsuccessful';
            }

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])
                ->post("{$this->paypalUrl}/v1/billing/subscriptions/{$subscriptionId}/revise", $data);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Subscription plan updated successfully', 'response' => $response->json()];
            } else {
                return ['success' => false, 'error' => 'Failed to update subscription plan', 'details' => $response->json()];
            }
        } catch (\Exception $e) {
            Log::info($e);
        }

    }

    public function subscriptionDetail($subscriptionId, $accessToken)
    {
        return $response = Http::withToken($accessToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])
            ->get("{$this->paypalUrl}/v1/billing/subscriptions/{$subscriptionId}");

    }

    public function cancelSubscription($subscriptionId, $accessToken, $reason)
    {

        $reason = ["reason" => ($reason) ? $reason : "Not satisfied with the service"];

        return $response = Http::withToken($accessToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])
            ->post("{$this->paypalUrl}/v1/billing/subscriptions/{$subscriptionId}/cancel", $reason);

    }

    public function verifyWebhook($accessToken, $verifyPayload)
    {
        return $response = Http::withToken($accessToken)
            ->post("{$this->paypalUrl}/v1/notifications/verify-webhook-signature", $verifyPayload);

    }

    public function createOrder($accessToken, $plan)
    {
        try {

            $data = [
                'intent'              => 'CAPTURE',
                'purchase_units'      => [
                    [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value'         => $plan->price,
                        ],
                    ],
                ],
                'application_context' => [
                    'return_url' => config('app.front_app_base_url') . '/home/suscripciones/pago/' . $plan->id . '?mobile_status=true',
                    'cancel_url' => config('app.front_app_base_url') . '/home/tablero/' . $plan->id . '?mobile_status=false',
                ],
            ];

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])
                ->post("{$this->paypalUrl}/v2/checkout/orders", $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::info($e);
        }

    }

    public function authorizeOrder($accessToken, $orderId)
    {
        try {
            $uniqueRequestId = Str::uuid()->toString();

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type'      => 'application/json',
                    'Accept'            => 'application/json',
                    'PayPal-Request-Id' => $uniqueRequestId,
                ])
                ->post("{$this->paypalUrl}/v2/checkout/orders/{$orderId}/capture", (object) []);

            return $response->json();
        } catch (\Exception $e) {
            Log::info($e);
        }

    }
}
