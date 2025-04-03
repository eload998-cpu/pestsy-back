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
            'aplication_id'=>'Aplicación',
            'aplication_place_id'=>'Lugar de aplicación',
            'product_id'=>'Producto',
            'dose'=>'Dosis'
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
            'aplication_id'=>'required',
            'aplication_place_id'=>'required',
            'product_id'=>'required',
            'dose'=>'required'

        ];
    }
}
