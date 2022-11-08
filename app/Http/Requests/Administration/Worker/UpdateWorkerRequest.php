<?php

namespace App\Http\Requests\Administration\Worker;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkerRequest extends FormRequest
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
            'first_name'=>'required',
            'last_name' =>'required',
            'email'     =>[
                'required',
                'email:filter',
                'unique:workers,email,'.$this->route('obrero'),
            ],
            'identification_number' =>'required',
            'cellphone'=>'required',
            'direction'=>'required'
        ];
    }
}
