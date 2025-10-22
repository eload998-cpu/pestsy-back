<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\ChangePasswordMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ChangePasswordController extends Controller
{

    public function canChangePassword($token)
    {

        $check_token = jwt_token_is_expired($token);

        return response()->json([
            "success" => true,
            "data"    => ($check_token) ? false : true,
        ]);

    }

    public function resetPassword(ResetPasswordRequest $request)
    {

        try {
            $password_reset = PasswordReset::where('token', $request->token)->first();

            if (! empty($password_reset)) {
                $user           = User::where('email', $password_reset->email)->first();
                $user->password = $request->password;
                $user->save();

                $password_reset->where('token', $request->token)->delete();
                return response()->json(
                    [
                        "success" => true,
                        "message" => "Contraseña actualizada con exito",
                    ]
                );
            }

            return response()->json(
                [
                    "success" => false,
                    "message" => "El token ha expirado",
                ]
            );

        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "El token ha expirado",
                ]
            );
        }

    }

    public function changePassword(ChangePasswordRequest $request)
    {

        if (! str_contains($request->emai, '@mail.com')) {

            $token = generate_jwt();

            $check_email = User::where('email', $request->email)->first();

            if (! empty($check_email)) {
                PasswordReset::create(
                    [
                        "email" => $request->email,
                        "token" => $token,
                    ]
                );

                Mail::to($request->email)
                    ->send(new ChangePasswordMail($token));
            }

        }
        return response()->json(
            [
                "success" => true,
                "message" => "Por favor revise su correo electronico",
            ]
        );

    }
}
