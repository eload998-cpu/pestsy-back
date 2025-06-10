<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Administration\Company;
use App\Models\GlobalError;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GlobalErrorHandlerController extends Controller
{

    public function __construct()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        GlobalError::create([
            "error" => json_encode($request->all()),
        ]);

        return response()->json(true);
    }

    public function importCompanies()
    {
        $path  = storage_path('app/companies.txt');
        $lines = File::lines($path);

        $dataStarted = false;
        $companies   = [];

        foreach ($lines as $line) {
            // Skip until data starts
            if (! $dataStarted) {
                if (Str::startsWith($line, 'COPY')) {
                    $dataStarted = true;
                }
                continue;
            }

            // End of COPY block
            if (trim($line) === '\.') {
                break;
            }

            // Parse the row
            $columns = explode("\t", $line);

            $companies[] = [
                'id'             => $columns[0],
                'name'           => $columns[1],
                'logo'           => $columns[2] !== '\N' ? $columns[2] : null,
                'phone'          => $columns[3] !== '\N' ? $columns[3] : null,
                'email'          => $columns[4] !== '\N' ? $columns[4] : null,
                'direction'      => $columns[5] !== '\N' ? $columns[5] : null,
                'created_at'     => $columns[6],
                'updated_at'     => $columns[7],
                'order_quantity' => $columns[8],
            ];
        }

        // Now loop or insert into DB
        foreach ($companies as $company) {

            Company::create($company);
        }

        return 'Done!';
    }

    public function importUsers()
    {
        $path  = storage_path('app/users.txt'); // Put your file in storage/app
        $lines = File::lines($path);

        $dataStarted = false;
        $users       = [];

        foreach ($lines as $line) {
            if (! $dataStarted) {
                if (Str::startsWith($line, 'COPY')) {
                    $dataStarted = true;
                }
                continue;
            }

            if (trim($line) === '\.') {
                break;
            }

            $columns = explode("\t", $line);

            $users[] = [
                'id'                         => $columns[0],
                'first_name'                 => $columns[1],
                'last_name'                  => $columns[2],
                'cellphone'                  => $columns[3] !== '\N' ? $columns[3] : null,
                'profile_picture'            => $columns[4] !== '\N' ? $columns[4] : null,
                'city_id'                    => $columns[5] !== '\N' ? $columns[5] : null,
                'email'                      => $columns[6],
                'email_verified_at'          => $columns[7] !== '\N' ? $columns[7] : null,
                'password'                   => $columns[8],
                'status_id'                  => $columns[10],
                'oauth_google_id'            => $columns[11] !== '\N' ? $columns[11] : null,
                'oauth_facebook_id'          => $columns[12] !== '\N' ? $columns[12] : null,
                'last_email_sent'            => $columns[14] !== '\N' ? $columns[14] : null,
                'remember_token'             => $columns[15] !== '\N' ? $columns[15] : null,
                'created_at'                 => $columns[16],
                'updated_at'                 => $columns[17],
                'tutorial_done'              => $columns[18] === 't',
                'paypal_subscription_id'     => $columns[19] !== '\N' ? $columns[19] : null,
                'active_subscription'        => $columns[20] === 't',
                'verify_paypal_subscription' => $columns[21] === 't',
            ];
        }

        foreach ($users as $user) {

            User::create($user);
        }

        return 'Users parsed successfully.';
    }

}
