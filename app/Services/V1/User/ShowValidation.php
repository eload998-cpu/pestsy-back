<?php
namespace App\Services\V1\User;

use Illuminate\Support\Facades\Validator;

class ShowValidation
{

    public function handle($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|numeric|digits_between:1,10|gt:0',
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
