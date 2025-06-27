<?php

namespace App\Http\Requests\Administration\Order\RodentControl;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidBitacores;
use App\Rules\ValidOwner;

class UpdateRodentControlRequest extends FormRequest
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
            'device_id'=>'Dispositivo',
            'product_id'=>'Producto',
            'order_id'=>'required',
            'device_number'=>'Numero de dispositivo',
            'location_id'=>'Ubicación',
            'bait_status'=>'Estado del cebo',
            'dose'=>'Dosis',
            'activity'=>'Actividad',
            'observation'=>'Observaciones'
           
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
            'device_id'=>'required',
            'product_id'=>'required',
            'order_id' => [
                'required',
                new ValidOwner,
            ],
            'device_number'=>'required',
            'location_id'=>'required',
            'bait_status'=>'required',
            'dose'=>'required',
            'activity'=>'required',
            'observation'=>'required',
            'bitacores'=>[
                new ValidBitacores($this->all())
            ]
        ];
    }
}
