<?php
namespace App\Services\V1\User;

use Illuminate\Support\Facades\Validator;

class ChangePasswordValidation
{

    public function handle($data)
    {
        $validator = Validator::make($data, [
            'email' => [
                'required',
                'email:filter',
            ],
        ]);

        if ($validator->fails()) {
            $response = [
                'success'       => false,
                'titleResponse' => 'error',
                'textResponse'  => $validator->errors()->all(),
                'data'          => null,
            ];
            return $response;
        }

        return [
            'success'       => true,
            'titleResponse' => 'Success',
            'textResponse'  => 'Success',
            'data'          => $data,
        ];
    }

}
