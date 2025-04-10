<?php
namespace App\Services\V1\User;

use App\Mail\UserCreationMail;
use App\Repositories\UserRespository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ChangePasswordService
{

    private $userRespository;

    public function __construct(UserRespository $userRespository)
    {
        $this->userRespository = $userRespository;

    }

    public function handle(Request $request)
    {
        try {

            $user           = $this->userRespository->findByCriteria(["email" => $request->email]);
            $password       = Str::random(10);
            $user->password = $password;
            $user->save();

            Mail::to($user->email)->send(new UserCreationMail($user, $password));


            $response = [
                'success'       => true,
                'titleResponse' => 'Success',
                'textResponse'  => 'User password changed successfully',
                'data'          => [],
            ];
            return $response;
        } catch (\Exception $e) {
            \Log::error($e);
            return $response = [
                'success'       => false,
                'titleResponse' => 'error',
                'textResponse'  => 'There was an error deleting the user',
                'data'          => [],
            ];
        }
    }

}
