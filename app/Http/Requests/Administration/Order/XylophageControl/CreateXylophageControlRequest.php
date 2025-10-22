<?php
namespace App\Http\Requests\Administration\Order\XylophageControl;

use Illuminate\Foundation\Http\FormRequest;

class CreateXylophageControlRequest extends FormRequest
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
            'pest_id'              => 'Xilofago',
            'product_id'           => 'Producto',
            'order_id'             => 'Orden',
            'aplication_id'        => 'Tratamiento aplicado',
            'construction_type_id' => 'Tipo de construccion',
            'affected_element_id'  => 'Elemento afectado',
            'treatment_date'       => 'Fecha de tratamiento',
            'next_treatment_date'  => 'Proxima fecha de tratamiento',
            'dose'                 => 'Dosis',
            'treated_area_value'   => 'Área tratada',
            'treated_area_unit'    => 'Unidad',
            'observation'          => 'Observaciones',
            'worker_id'            => 'Técnico que ejecuta',

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
            'order_id'             => 'required',
            'worker_id'             => 'required',
            'aplication_id'        => 'required',
            'construction_type_id' => 'required',
            'affected_element_id'  => 'required',
            'treatment_date'       => 'required',
            'next_treatment_date'  => 'required',
            'dose'                 => 'required',
            'treated_area_value'   => 'required',
            'treated_area_unit'    => 'required',

        ];
    }
}
