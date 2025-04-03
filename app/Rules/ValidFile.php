<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidFile implements Rule
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

       return $this->check_extension($value); 
    }


    private function check_extension($value)
    {
        $valid_ext = array('pdf');

        $count=count($value);
        $i=0;

        foreach ($value as $key => $v) 
        {
            // Getting file name
             $filename = $v->getClientOriginalName();
            // file extension
            $file_extension = $v->getClientOriginalExtension();

            // Check extension
            if(in_array($file_extension,$valid_ext))
            {
     
                $i++;
            }

           
        }

        return ($i==$count)?true:false;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Verifique que los archivos posean una extension valida (pdf)';
    }
}
