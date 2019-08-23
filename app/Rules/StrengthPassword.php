<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrengthPassword implements Rule
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
        //si no hi ha password donem la validació correcta
        if ( ! $value) {
        	return true;
        }

        //Volem com a minim una majuscula
        $uppercase          = preg_match('@[A-Z]@', $value);
        //Volem com a minim una minuscula
        $lowercase          = preg_match('@[a-z]@', $value);
        //Volem com a minim un numero
        $number             = preg_match('@[0-9]@', $value);
        //Volem com a minim 8 digits
	    $length             = strlen($value) >= 8;

        $success = true;

        if ( ! $uppercase || ! $lowercase || ! $number || ! $length) {
        	$success = false;
        }

        return $success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
	    return __('El :attribute debe tener 8 caracteres, un número, una letra mayúscula y una letra minúscula');
    }
}
