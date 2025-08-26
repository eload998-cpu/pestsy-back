<?php
namespace App\Services;

use App\Models\Module\Client;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ClientService
{

    public static function add($id)
    {
        $user = Auth::user();

        $client_name = explode("-", $client_id);
        $client_name = explode(" ", $client_name[1]);
        $email_name  = str_replace(" ", "_", $client_name[0]);

        return $client = Client::create(
            [
                "first_name" => $client_name[0],
                "email"      => $email_name . Str::random(8) . "@mail.com",
                "date"       => Carbon::now(),
                "company_id" => $user->company_id,
            ]
        )->id;

    }
}
