<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPassword implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        //no accents
        if(!preg_match('*[áàãâéèêíìîõóòôúùû]*',$value))
        {
            //no whitespaces
            if(!preg_match('/\s/',$value))
                return $has_duplicates=$this->check_duplicates($value);         
        }

         return false;  
    }


    private function check_duplicates($string)
    {
        $duplicates=0;
        foreach(str_split($string) as $i =>$val)
        {
            
                if($i>0)
                {
                    if($string[$i-1]==$string[$i])
                    {
                        $duplicates++;

                        if($duplicates>2)
                        {
                            return false;
                        }
    
                    }else
                    {
                        $duplicates=1;
                    }
                }else
                {
                    $duplicates=1;
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
        return 'La contraseña no puede repetir un caracteres de forma consecutiva.';
    }
}
