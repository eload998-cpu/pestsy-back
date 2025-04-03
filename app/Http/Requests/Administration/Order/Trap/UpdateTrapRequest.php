<?php

namespace App\Http\Requests\Administration\Order\Trap;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidOwner;

class UpdateTrapRequest extends FormRequest
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
            'station_number' => 'Numero de estación',
            'device_id' => 'Dispositivo',
            'product_id' => 'Producto',
            'dose' => 'Dosis',

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
            'station_number' => 'required',
            'device_id' => 'required',
            'product_id' => 'required',
            'dose' => 'required',
            'order_id' => [
                'required',
                new ValidOwner,
            ]
        ];
    }
}
