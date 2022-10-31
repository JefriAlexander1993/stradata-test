<?php

namespace App;

use App\PersonPublic;
use Illuminate\Support\Facades\DB;
use Validator;
// app includes


/**
 * Querys for Person Table
 */
class UserModel
{


    /**
     * get validator for PersonPublic
     * @param array $data information from form
     * @return Object Validator
     */
    public static function getValidator($data, $type = null)
    {


        if ($type == 'register') {
            $niceNames = array(
                'name.required'   => 'El campo nombre es requerido.',
                'name.max'   => 'La cantidad de caracteres del campo nombre supera las establecidas.',
                'email.required'   => 'El campo email, es requerdo.',
                'email.email'   => 'El campo email, debe ser tipo email.',
                'email.unique'   => 'El email, ingresado ya esta asociado a un usuario, por favor ingresa uno diferente.',
                'password.required'   => 'El campo contraseña, es requerido.',
            );

            $validator = Validator::make($data, [
                'name' => ['required', 'max:40'],
                'email' => ['required', 'unique:users,email', 'email'],
                'password' => ['required', 'min:7'],
            ], $niceNames);
        }

        $niceNames = array(
            'email.required'   => 'El campo email, es requerdo.',
            'email.email'   => 'El campo email, debe ser tipo email.',
            'password.required'   => 'El campo contraseña, es requerido.',
        );

        $validator = Validator::make($data, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], $niceNames);



        return  $validator;
    }
}
