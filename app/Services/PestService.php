<?php
namespace App\Services;

use App\Models\Module\Pest;
use Illuminate\Support\Facades\Auth;

class PestService
{

    public static function add($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = Pest::create(
            [
                "common_name"     => $name,
                "scientific_name" => $name,
                "is_xylophagus"   => true,
                "company_id"      => $user->company_id,

            ]
        )->id;

    }
}
