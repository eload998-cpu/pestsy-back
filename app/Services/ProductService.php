<?php
namespace App\Services;

use App\Models\Module\Product;
use Illuminate\Support\Facades\Auth;

class ProductService
{

    public static function add($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = Product::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }
}
