<?php

namespace App;

use App\PersonPublic;
use Illuminate\Support\Facades\DB;
use Validator;
// app includes

/**
* Querys for Person Table
*/

class PersonPublicpreviousQuery {

    public static function savePersonPublicPrevious( $uuid, $data ) {

        $person = new PersonPublicPrevious;
        $person->uuid = $uuid;
        $person->data = json_encode( $data );
        $person->save();

    }
    public static function getPersonPublicPrevious( $uuid ) {

        $person = PersonPublicPrevious::where( 'uuid', '=', $uuid )->select( 'uuid', 'data' )->first() ?? '';

        $result = [
            'result'=>[
                'data' => $person,
                'uuid' =>$uuid ,
                'search_name' => '',
                'percent_search' => '',
                'execution_status' => "Registros anteriores",
                "error" => false,
                'count' =>1,
                "class" => 'alert alert-info'
            ],
            'code'=>200
        ];
        return $result;

    }

}
