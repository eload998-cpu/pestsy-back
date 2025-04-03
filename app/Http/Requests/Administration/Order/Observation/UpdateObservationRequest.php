<?php

namespace App\Http\Requests\Administration\Order\Observation;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidOwner;

class UpdateObservationRequest extends FormRequest
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

    public function attributes()
    {
        return [
            'observation'=>'Observación',
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'observation'=>'required',
            'order_id' => [
                'required',
                new ValidOwner,
            ]
        ];
    }
}
