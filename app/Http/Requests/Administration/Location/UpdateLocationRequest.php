<?php
namespace App\Http\Requests\Administration\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
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
            'name'          => 'required',
            'is_legionella' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('is_legionella')) {
            $this->merge([
                'is_legionella' =>($this->is_legionella == 'true') ? true : false,
            ]);
        }
    }
}
