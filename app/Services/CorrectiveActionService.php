<?php
namespace App\Services;

use App\Models\Module\CorrectiveAction;
use Illuminate\Support\Facades\Auth;

class CorrectiveActionService
{

    public static function add($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = CorrectiveAction::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }
}
