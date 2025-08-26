<?php
namespace App\Services;

use App\Models\Module\AffectedElement;
use Illuminate\Support\Facades\Auth;

class AffectedElementService
{

    public static function add($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = AffectedElement::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }
}
