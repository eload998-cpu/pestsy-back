<?php
namespace App\Http\Controllers\API\Administration;

use App\Models\Administration\DeviceToken;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushTokenController extends Controller
{
    public function register(Request $request, FirebaseService $firebase)
    {
        $user = Auth::user();

        $data = $request->validate([
            'token'     => 'required|string|max:512',
            'platform'  => 'nullable|string|max:32',
            'device_id' => 'nullable|string|max:128',
        ]);

        $deviceToken = DeviceToken::updateOrCreate(
            ['token' => $data['token']],
            [
                'user_id'      => $user->id,
                'platform'     => $data['platform'] ?? null,
                'device_id'    => $data['device_id'] ?? null,
                'last_seen_at' => now(),
            ]
        );

        // ---- Role rule (customize this to your roles/permissions) ----
        $topic = 'can_create_orders';

        $canCreateOrders = $this->userCanCreateOrders($user);

        if ($canCreateOrders) {
            $firebase->subscribeToTopic($topic, [$deviceToken->token]);
        } else {
            $firebase->unsubscribeFromTopic($topic, [$deviceToken->token]);
        }

        return response()->json([
            'ok'         => true,
            'subscribed' => $canCreateOrders,
            'topic'      => $topic,
        ]);
    }

    private function userCanCreateOrders($user): bool
    {

        return $user->roles()->first()->name !== 'system_user';
    }
}
