<?php

namespace App\Http\Requests\Administration\Order\Signature;

use Illuminate\Foundation\Http\FormRequest;

class CreateSignatureRequest extends FormRequest
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
            'order_id'=>'required',
            'signature_pad_client'=>'required',
            'signature_pad_worker'=>'required'
        ];
    }
}
