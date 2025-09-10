<?php
namespace App\Services;

use App\Models\Module\Aplication;
use Illuminate\Support\Facades\Auth;

class ApplicationService
{

    public static function add($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = Aplication::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }
}
