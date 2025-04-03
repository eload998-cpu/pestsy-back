<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule as RuleVal;
use Illuminate\Support\Facades\Log;

class ValidClientEmails implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $request;
    protected $update;
    protected $client_id;

    public function __construct($request,$update,$client_id)
    {
        $this->request = $request;
        $this->update = $update;
        $this->client_id = $client_id;

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


        $mainEmail = strtolower($this->request['email']);

        if (!$value || !is_array($value)) {
            return true; // if no emails are provided, validation should pass
        }

        $emails = collect($value)->pluck('email');


        $emails = $emails->map(function($e){
            return $e = strtolower($e);
        });

        // Check if main email is in the optional emails array
        if ($emails->contains($mainEmail)) {
            return false;
        }

        // Check for duplicate emails in the optional emails array
        if ($emails->count() !== $emails->unique()->count()) {
            return false;
        }

        foreach ($emails as $email) {

            if(empty($email))
            {
                return false;

            }

            $validator = Validator::make(['email' => $email], [
                'email' => 'email:filter',
                'email' => (!$this->update) ? 'unique:client_emails,email':RuleVal::unique('client_emails')->ignore($this->client_id,'client_id'),
                'email' => 'unique:clients,email'
            ]);
            if ($validator->fails()) {
                return false;
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
        return 'Verifique que los correos opcionales sean validos,no esten duplicados o no hayan sido asignados a otros clientes';
    }
}
