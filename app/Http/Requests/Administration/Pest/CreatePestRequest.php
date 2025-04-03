<?php

namespace App\Http\Requests\Administration\Pest;

use Illuminate\Foundation\Http\FormRequest;

class CreatePestRequest extends FormRequest
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
            'common_name'=>'required',
            'scientific_name'=>'required'

        ];
    }
}
