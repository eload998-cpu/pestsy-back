<?php
namespace App\Services;

use App\Models\Module\Device;
use Illuminate\Support\Facades\Auth;

class DeviceService
{

    public static function add($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = Device::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }
}
