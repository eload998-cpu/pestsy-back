<?php

namespace App\Http\Requests\Administration\Order\Image;

use App\Rules\ValidImage;
use App\Rules\ValidOwner;
use Illuminate\Foundation\Http\FormRequest;

class CreateImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'images' => [
                'required',
                new ValidImage,
            ],
            'order_id' => [
                'required'
            ],
            'max:2240',

        ];
    }
}
