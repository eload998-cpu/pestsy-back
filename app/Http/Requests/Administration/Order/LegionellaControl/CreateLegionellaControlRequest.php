<?php
namespace App\Http\Requests\Administration\Order\LegionellaControl;

use Illuminate\Foundation\Http\FormRequest;

class CreateLegionellaControlRequest extends FormRequest
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
            'order_id'                => 'Orden',
            'location_id'             => 'Instalacion',
            'desinfection_method_id'  => 'Metodo de desinfeccion',
            'inspection_result'       => 'resultado inspeccion',
            'last_treatment_date'     => 'ultima fecha de tratamiento',
            'next_treatment_date'     => 'proxima fecha de tratamiento',
            'code'                    => 'Codigo',
            'sample_required'         => 'Requiere muestra',
            'water_temperature'       => 'Temperatura del agua',
            'residual_chlorine_level' => 'Nivel de cloro residual',
            'observation'             => 'Observaciones',

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
            'order_id'                => 'required',
            'location_id'             => 'required',
            'desinfection_method_id'  => 'required',
            'inspection_result'       => 'required',
            'last_treatment_date'     => 'required',
            'next_treatment_date'     => 'required',
            'code'                    => 'nullable|string',
            'sample_required'         => 'boolean',
            'water_temperature'       => 'nullable|numeric',
            'residual_chlorine_level' => 'nullable|numeric',
            'observation'             => 'nullable|required',

        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('sample_required')) {
            $this->merge([
                'sample_required' => filter_var($this->sample_required, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
