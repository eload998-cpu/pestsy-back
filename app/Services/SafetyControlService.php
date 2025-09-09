<?php
namespace App\Services;

use App\Models\Module\SafetyControl;
use Illuminate\Support\Facades\Auth;

class SafetyControlService
{

    public static function add($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = SafetyControl::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }
}
