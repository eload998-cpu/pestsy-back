<?php
namespace App\Services;

use App\Models\Module\ConstructionType;
use Illuminate\Support\Facades\Auth;

class ConstructionTypeService
{

    public static function add($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = ConstructionType::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }
}
