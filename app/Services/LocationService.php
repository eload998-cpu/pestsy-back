<?php
namespace App\Services;

use App\Models\Module\Location;
use Illuminate\Support\Facades\Auth;

class LocationService
{

    public static function add($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = Location::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }
}
