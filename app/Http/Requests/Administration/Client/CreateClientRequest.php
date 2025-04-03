<?php

namespace App\Http\Requests\Administration\Client;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidClientEmails;

class CreateClientRequest extends FormRequest
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


    protected function prepareForValidation()
    {
        $this->merge([
            'email' => strtolower($this->email),
        ]);
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
                'unique:clients,email'
            ],
            'date'=>'required',
            'identification_type'=>'required',
            'identification_number' =>'required',
            'cellphone'=>'required',
            'direction'=>'required',
            'emails.*'=>'required',
            'emails'=>[
                new ValidClientEmails($this->all(),false,null)
            ]
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'Nombre',
            'last_name' => 'Apellido',
            'date'=>'Fecha de ingreso',
            'identification_type'=>'Tipo de idenficiacion',
            'email' => 'Correo',
            'celphone' => 'Telefono',
            'identification_number' => 'Numero de identificacion',
            'direction' => 'Dirección'

        ];
    }

    public function messages()
    {
        return [

            'email.unique' => 'El correo ya se encuentra registrado',
            'email.email' => 'La direccion de correo no es valida',


        ];
    }
}
