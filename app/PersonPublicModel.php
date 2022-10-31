<?php

namespace App;

use App\PersonPublic;
use Illuminate\Support\Facades\DB;
use Validator;
// app includes


/**
 * Querys for Person Table
 */
class PersonPublicModel {


    /**
     * get validator for PersonPublic
     * @param array $data information from form
     * @return Object Validator
     */
    public static function getValidator($data) {

        $niceNames = array(
            'name.required'   => 'El campo nombre es requerido.',
            'name.max'   => 'La cantidad de caracteres del campo nombre supera las establecidas.',
        );

        $validator = Validator::make($data, [
            'name' => ['required','max:40'],

        ],$niceNames );

        return  $validator;

    }

        /**
     * get validator for PersonPublic
     * @param array $data information from form
     * @return Object Validator
     */
    public static function consultPersonPublic($search) {

        $personPublics = PersonPublic::where('name', 'like', "%$search%")->get();
        $data = DB::select("select *,100.00 as porcentage from person_publics where soundex(name) =  SOUNDEX( '$search' )");


        return [

            'personPublics'=>$personPublics,
            'data'=>$data


        ];
    }

}
