<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\PersonPublicExport;
use App\PersonPublic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Str;
use App\PersonPublicModel;
use Illuminate\Support\Facades\Validator;

class PersonPublicController extends Controller
{
    private $arrayPersonPublicFilter = [];
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function fileExport(Request $request)
    {

        return response()->json(new PersonPublicExport($request->data, Carbon::now()->format('Ymdhms') . '-person-public-collection.xlsx'), 200);

        // new PersonPublicExport($request->data,$filename);

        // $fullPath = Storage::disk('local')->path($filename);

        // return response()->json([
        //     'data' => $fullPath,
        //     'message' => 'Products are successfully exported.'
        // ], 200);


    }

    public function filterPersonPublic(Request $request)
    {
        $search = is_null($request->data['search']) ? "" : $request->data['search'];
        $percentage = is_null($request->data["percentage"]) ? "" : $request->data["percentage"];
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
                        'uuid' => Str::uuid(),
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
                        'data' => $data,
                        'uuid' => Str::uuid(),
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
                $this->_levenshtein($value->name, $search, $value, $percentage);
            }

            return response()->json(
                [
                    'data' => $this->arrayPersonPublicFilter,
                    'uuid' => Str::uuid(),
                    'search_name' => $search,
                    'percent_search' => $percentage,
                    'execution_status' => "Registros con considencias",
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
                    'uuid' => Str::uuid(),
                    'search_name' => $search,
                    'percent_search' => $percentage,
                    'execution_status' => "Error del sistema",
                    "error" => true,
                    'count' => 0,
                    "class" => 'alert alert-danger'
                ],
                500
            );
        }
    }

    function _levenshtein($str1, $str2, $value, $percentage)
    {

        $percentageCoincidence = doubleval(number_format((1 - levenshtein($str1, $str2) / max(strlen($str1), strlen($str2))) * 100, 2));

        if ($percentageCoincidence == doubleval($percentage)) {

            array_push($this->arrayPersonPublicFilter, [
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
