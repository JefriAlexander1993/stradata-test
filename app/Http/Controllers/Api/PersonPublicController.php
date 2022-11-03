<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\PersonPublicModel;
use App\PersonPublicpreviousModel;


class PersonPublicController extends Controller
{
    private $arrayPersonPublicFilter = [];
    public function __construct()
    {
        $this->middleware('jwtauth');
    }

    /*
    |-------------------------------------------------------------------------------
    | filterPersonPublic
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/filterPersonPublic
    | Method:         Post
    | Description:    Se retorna un objecto con todos las persona publicas que cumplen la condición.
    */
    public function filterPersonPublic(Request $request)
    {
        $search = is_null($request->data['search']) ? "" : $request->data['search'];
        $percentage = is_null($request->data["percentage"]) ? "" : $request->data["percentage"];
        $uuid = Str::uuid();
        $data = [
            'name' => $search,
            'percentage' => $percentage
        ];

        try {


            $validate = PersonPublicModel::getValidator($data);

            if ($validate->fails()) {

                return response()->json(
                    [
                        'data' => $validate->errors(),
                        'uuid' => $uuid,
                        'search_name' => $search,
                        'percent_search' => $percentage,
                        'execution_status' => "Los datos ingresados no cumple con lo requerido",
                        "error" => true,
                        'count' => 0,
                        "class" => 'alert alert-danger'
                    ],
                    400
                );
            }

            $consultPersonPublic = PersonPublicModel::consultPersonPublic($search);

            if (count($consultPersonPublic['data']) > 0) {
                return response()->json(
                    [
                        'data' => $consultPersonPublic['data'],
                        'uuid' => $uuid,
                        'search_name' => $search,
                        'percent_search' => 100.00,
                        'execution_status' => "Registro encontrado",
                        "error" => false,
                        'count' => count($data),
                        "class" => 'alert alert-success'
                    ],
                    200
                );
            }


            foreach ($consultPersonPublic['personPublics'] as $key => $value) {
                $this->_levenshtein($value->name, $search, $value, $percentage,$uuid);
            }

            PersonPublicpreviousModel::savePersonPublicPrevious($uuid,$this->arrayPersonPublicFilter);

            return response()->json(
                [
                    'data' => $this->arrayPersonPublicFilter,
                    'uuid' => $uuid,
                    'search_name' => $search,
                    'percent_search' => $percentage,
                    'execution_status' =>count($this->arrayPersonPublicFilter)>0? "Registros con considencias":'Sin concidencia',
                    "error" => false,
                    'count' => count($this->arrayPersonPublicFilter),
                    "class" => 'alert alert-info'
                ],
                200
            );

        } catch (\Exception $ex) {
            return response()->json(
                [
                    'data' => [],
                    'uuid' => $uuid,
                    'search_name' => $search,
                    'percent_search' => $percentage,
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
    | previousFilterPersonPublic
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/previousFilterPersonPublic
    | Method:         Post
    | Description:    Se retorna un objecto con todos las personas publicas buscadas anteriormente.
    */
    public function previousFilterPersonPublic(Request $request)
    {

        try {

            $personPublicPrevious= PersonPublicpreviousModel::getPersonPublicPrevious($request->uuid);

            return response()->json(
                [
                    'data' =>  $personPublicPrevious,
                    'uuid' => $request->uuid,
                    'search_name' => '',
                    'percent_search' => '',
                    'execution_status' => "Registros anteriores",
                    "error" => false,
                    'count' =>1,
                    "class" => 'alert alert-info'
                ],
                200
            );
        } catch (\Exception $ex) {

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

    function _levenshtein($str1, $str2, $value, $percentage,$uuid)
    {

        $percentageCoincidence = doubleval(number_format((1 - levenshtein($str1, $str2) / max(strlen($str1), strlen($str2))) * 100, 2));

        if ($percentageCoincidence == doubleval($percentage)) {

            array_push($this->arrayPersonPublicFilter, [
                'uuid'=>$uuid,
                "porcentage" => $percentageCoincidence,
                "name" => $value->name,
                "person_type" => $value->person_type,
                "type_of_load" => $value->type_of_load,
                "department" => $value->department,
                "municipality" => $value->municipality,
            ]);
        }

        if($percentage == ""){

            array_push($this->arrayPersonPublicFilter, [
                'uuid'=>$uuid,
                "porcentage" => $percentageCoincidence,
                "name" => $value->name,
                "person_type" => $value->person_type,
                "type_of_load" => $value->type_of_load,
                "department" => $value->department,
                "municipality" => $value->municipality,
            ]);
        }
    }
}
