<?php

namespace App\Http\Requests\Administration\Order\Lamp;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidOwner;

class UpdateLampRequest extends FormRequest
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
            'lamp_not_working'=>'Lampara en mal funcionamiento',
            'fluorescent_change'=>'Cambio de fluorescente',
            'station_number'=>'Numero de estación',
            'observation'=>'Observacion',
            'quantity_replaced'=>'Cantidad reemplazada'

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
            'fluorescent_change'=>'required',
            'lamp_not_working'=>'required',
            'station_number'=>'required',
            'observation'=>'required',
            'quantity_replaced'=>'required_if:fluorescent_change,yes',
            'order_id' => [
                'required',
                new ValidOwner,
            ]
        ];
    }
}
