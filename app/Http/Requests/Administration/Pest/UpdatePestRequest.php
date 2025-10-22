<?php
namespace App\Http\Requests\Administration\Pest;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePestRequest extends FormRequest
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
            'common_name'     => 'required',
            'scientific_name' => 'required',
            'is_xylophagus'   => 'nullable|boolean',

        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('is_xylophagus')) {
            $this->merge([
                'is_xylophagus' => ($this->is_xylophagus == 'true') ? true : false,
            ]);
        }
    }
}
