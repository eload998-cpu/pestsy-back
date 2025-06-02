<?php
namespace App\Http\Requests\Administration\Order\XylophageControl;

use App\Rules\ValidOwner;
use Illuminate\Foundation\Http\FormRequest;

class UpdateXylophageControlRequest extends FormRequest
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
            'pest_id'              => 'Tipo de xilofago',
            'product_id'           => 'Producto',
            'order_id'             => 'Orden',
            'applied_treatment_id' => 'Tratamiento aplicado',
            'construction_type_id' => 'Tipo de construccion',
            'affected_element_id'  => 'Elemento afectado',
            'treatment_date'       => 'Fecha de tratamiento',
            'next_treatment_date'  => 'Proxima fecha de tratamiento',
            'observation'          => 'Observaciones',
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
            'pest_id'              => 'required',
            'product_id'           => 'required',
            'order_id'             => [
                'required',
                new ValidOwner,
            ],
            'applied_treatment_id' => 'required',
            'construction_type_id' => 'required',
            'affected_element_id'  => 'required',
            'treatment_date'       => 'required',
            'next_treatment_date'  => 'required',
            'observation'          => 'required',

        ];

    }
}
