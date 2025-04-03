<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidBitacores implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if (count($value)) {
            foreach ($value as $key => $v) {
                if ($v["pest_id"] == "" || $v["quantity"] == "") {
                    return false;
                }
            }
        }

        return true;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Las capturas no pueden estar vacias';
    }
}
