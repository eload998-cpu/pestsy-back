<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'company_email' => strtolower($this->company_email),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        updateConnectionSchema("administration");

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                'email:filter',
                'unique:users,email,' . Auth::user()->id,
            ],
            'cellphone' => 'required',
            'password' => 'nullable',
            'company_name' => 'required',
            'company_email' => [
                'required',
                'email:filter',
                'unique:companies,email,' . Auth::user()->company->id,
            ],
            'password_confirmation' => [
                Rule::requiredIf(function () {
                    return !empty($this->request->get('password'));
                }),
            ],
        ];
    }
}
