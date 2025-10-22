<?php
namespace App\Http\Requests\Administration\Order\Fumigation;

use Illuminate\Foundation\Http\FormRequest;

class CreateFumigationRequest extends FormRequest
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
            'aplication_id'    => 'Aplicación',
            'location_id'      => 'Ubicación',
            'product_id'       => 'Producto',
            'dose'             => 'Dosis',
            'worker_id'        => 'Técnico responsable',
            'application_time' => 'Hora de aplicación',
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
            'aplication_id'    => 'required',
            'location_id'      => 'required',
            'product_id'       => 'required',
            'dose'             => 'required',
            'worker_id'        => 'required',
            'application_time' => 'required',

        ];
    }
}
