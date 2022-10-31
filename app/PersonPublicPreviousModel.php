<?php

namespace App;

use App\PersonPublic;
use Illuminate\Support\Facades\DB;
use Validator;
// app includes


/**
 * Querys for Person Table
 */
class PersonPublicpreviousModel {


    public static function savePersonPublicPrevious($uuid , $data) {

        $person =new PersonPublicPrevious;
        $person->uuid= $uuid;
        $person->data= json_encode($data);
        $person->save();

    }
    public static function getPersonPublicPrevious($uuid){

        $person =PersonPublicPrevious::where('uuid','=',$uuid)->select('uuid','data')->first();
        return $person;

    }

}
