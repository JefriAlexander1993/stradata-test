<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\PersonPublicValidate;
use App\PersonPublicQuery;
use App\PersonPublicPreviousQuery;
use DB;

class PersonPublicController extends Controller {
    private $arrayPersonPublicFilter = [];

    public function __construct() {
        $this->middleware( 'jwtauth' );
    }

    /*
    |-------------------------------------------------------------------------------
    | filterPersonPublic
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/filterPersonPublic
    | Method:         Post
    | Description:    Se retorna un objecto con todas las persona publicas que cumplen la condición.
    */

    public function filterPersonPublic( Request $request ) {

        //validate formate data.
        $uuid = Str::uuid();
        $data = PersonPublicValidate::validateFormateData( $request->data );

        try {

            $resultValidate = PersonPublicValidate::getValidator( $data, $uuid );

            if ( count( $resultValidate )>0 ) {
                return response()->json( $resultValidate['result'], $resultValidate['code'] );

            }

            $consultPersonPublic = PersonPublicQuery::consultPersonPublic( $data['name'], $uuid );

            if ( $consultPersonPublic['type'] == 'hundredPercentMatch' ) {

                return response()->json( $consultPersonPublic['result'], $consultPersonPublic['code'] );

            }

            foreach ( $consultPersonPublic['result']['data'] as $key => $value ) {
                $this->_levenshtein( $value->name, $data['name'], $value, $data['percentage'], $uuid );
            }

            PersonPublicPreviousQuery::savePersonPublicPrevious( $uuid, $this->arrayPersonPublicFilter );

            return response()->json(
                [
                    'data' => $this->arrayPersonPublicFilter,
                    'uuid' => $uuid,
                    'search_name' => $data['name'],
                    'percent_search' => $data['percentage'],
                    'execution_status' =>count( $this->arrayPersonPublicFilter )>0? 'Registros con considencias':'Sin concidencia',
                    'error' => false,
                    'count' => count( $this->arrayPersonPublicFilter ),
                    'class' => 'alert alert-info'
                ],
                200
            );

        } catch ( \Exception $ex ) {
            return response()->json(
                [
                    'data' => [],
                    'uuid' => $uuid,
                    'search_name' => $data['name'],
                    'percent_search' => $data['percentage'],
                    'execution_status' => 'Error del sistema ' .$ex->getMessage().' '.$ex->getLine(),
                    'error' => true,
                    'count' => 0,
                    "class" => 'alert alert-danger'
                ],
                500
            );
        }
    }

    /*
    |-------------------------------------------------------------------------------
    | previousFilterPersonPublic
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/previousFilterPersonPublic
    | Method:         Post
    | Description:    Se retorna un objecto con todos las personas publicas buscadas anteriormente.
    */

    public function previousFilterPersonPublic( Request $request ) {

        try {

            $personPublicPrevious = PersonPublicPreviousQuery::getPersonPublicPrevious( $request->uuid );

            return response()->json(
                $personPublicPrevious['result'],
                $personPublicPrevious['code']
            );
        } catch ( \Exception $ex ) {

            return response()->json(
                [
                    'data' => [],
                    'uuid' =>$request->uuid,
                    'search_name' => '',
                    'percent_search' => '',
                    'execution_status' => "Error del sistema " .$ex->getMessage().' '.$ex->getLine(),
                    "error" => true,
                    'count' => 0,
                    "class" => 'alert alert-danger'
                ],
                500
            );
        }

    }
    /*
    |-------------------------------------------------------------------------------
    | _levenshtein
    |-------------------------------------------------------------------------------
    | Method:         Post
    | Description:    Asignación del porcentaje de similitud.
    */

    function _levenshtein( $str1, $str2, $value, $percentage, $uuid ) {

        $percentageCoincidence = doubleval( number_format( ( 1 - levenshtein( $str1, $str2 ) / max( strlen( $str1 ), strlen( $str2 ) ) ) * 100, 2 ) );

        if ( $percentageCoincidence == doubleval( $percentage ) ) {

            array_push( $this->arrayPersonPublicFilter, [
                'uuid'=>$uuid,
                "porcentage" => $percentageCoincidence,
                "name" => $value->name,
                "person_type" => $value->person_type,
                "type_of_load" => $value->type_of_load,
                "department" => $value->department,
                "municipality" => $value->municipality,
            ] );
        }

        if ( $percentage == "" ) {

            array_push( $this->arrayPersonPublicFilter, [
                'uuid'=>$uuid,
                "porcentage" => $percentageCoincidence,
                "name" => $value->name,
                "person_type" => $value->person_type,
                "type_of_load" => $value->type_of_load,
                "department" => $value->department,
                "municipality" => $value->municipality,
            ] );
        }
    }
}
