<?php

namespace App\Http\Requests\Administration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\ValidPassword;
use App\Rules\ValidUpperCasePassword;

class CreateUserRequest extends FormRequest
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
            'company_name'=>'required',
            'first_name' => 'required|string|min:2|max:50',
            'last_name' => 'required|string|min:2|max:50',
            'email' => [
                "nullable",
                "email:filter",
                "unique:pgsql.administration.users,email"
            ],
            'password' => [
            "required",
            "min:8",
            "confirmed",
            new ValidPassword,
            new ValidUpperCasePassword
        ],
            //'country_id'=>'required',
            //'state_id'  =>'required',
           // 'city_id'  =>'required',
            'password_confirmation'=>'required',
            'cellphone' => 'sometimes|nullable'
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'Nombre',
            'last_name' => 'Apellido',
            'email' => 'Correo',
            'password' => 'Contraseña',
            'password_confirmation'=>'Confirmacion de contraseña',
            'celphone' => 'Telefono',
            'country_id' => 'Pais',
            'state_id' => 'Estado',
            'city_id' => 'Ciudad',

        ];
    }




}
