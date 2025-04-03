<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    private $user;
    public function __construct(User $user)
    {

        $this->user = $user;
    }

    public function inactivateUser($userId)
    {
        try {

            $user = $this->user->find($userId);
            $user->paypal_subscription_id = null;
            $user->active_subscription = false;
            $user->save();

        } catch (\Exception $e) {
            \Log::error($e);
        }

    }
}
