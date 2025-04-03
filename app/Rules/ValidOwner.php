<?php

namespace App\Rules;

use App\Models\Module\Order;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\Module\SystemUser;
use App\Models\Module\Operator;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ValidOwner implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

    
        $status_type = StatusType::where('name', 'order')->first();
        $pending_status = Status::where('status_type_id', $status_type->id)->where('name', 'pending')->first();
        $process_status = Status::where('status_type_id', $status_type->id)->where('name', 'in process')->first();

        $user = Auth::user();
        $user_role = $user->roles()->first()->name;

        $module_name = $user->module_name;
        $user_id = $user->id;
        updateConnectionSchema($module_name);
        switch ($user_role) {

            default:
                if ($user_role == 'fumigator' || $user_role == 'administrator' || $user_role == 'super_administrator') {
                    $user_id = $user->id;
                }
                break;

        }
   
        return Order::where(['id' => $value, 'user_id' => $user_id, 'status_id' => $pending_status->id])
        ->orWhere(['id' => $value, 'user_id' => $user_id, 'status_id' => $process_status->id])->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La orden es invalida';
    }
}
