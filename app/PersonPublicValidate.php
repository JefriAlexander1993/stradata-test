<?php

namespace App;

use App\PersonPublic;
use Illuminate\Support\Facades\DB;
use Validator;
// app includes

/**
* Querys for Person Table
*/

class PersonPublicValidate {

    /**
    * get validator for PersonPublic
    * @param array $data information from form
    * @return Object Validator
    */
    public static function getValidator($data, $uuid) {

        $niceNames = array(
            'name.required'   => 'El campo nombre es requerido.',
            'name.max'   => 'La cantidad de caracteres del campo nombre supera las establecidas.',
        );

        $validator = Validator::make( $data, [
            'name' => ['required', 'max:40'],

        ], $niceNames );

        if ($validator->fails()) {

            return
            [
              'result'=>
				[
					'data' =>$validator->errors(),
					'uuid' => $uuid,
					'search_name' => $data['name'],
					'percent_search' => $data["percentage"],
					'execution_status' => "Los datos ingresados no cumple con lo requerido",
					'error' => true,
					'count' => 0,
					'class' => 'alert alert-danger'
				 ],
				'code'=>401
            ];
        } else {

            return  [];
        }

    }

    public static function validateFormateData($data) {

        $search = is_null($data['search']) ? "" : $data['search'];
        $percentage = is_null($data["percentage"]) ? "" : $data["percentage"];

        return $data = [
            'name' => $search,
            'percentage' => $percentage
        ];
    }

}
