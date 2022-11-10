<?php

namespace App;

use App\PersonPublic;
use Illuminate\Support\Facades\DB;
use Validator;
// app includes

/**
* Querys for Person Table
*/

class PersonPublicQuery {

    public static function consultPersonPublic( $search ) {

        $hundredPercentMatch = PersonPublic::whereFuzzy( 'name', $search )->having( '_fuzzy_relevance_', '=', 100 )->first() ?? [];

        $personPublics = PersonPublic::where( 'name', 'like', "%$search%" )->get();

        $result = [];

        if ( count($hundredPercentMatch)>0 ) {
            $result = [
				'type'=>'hundredPercentMatch',
				'result' =>[
						'data' => $hundredPercentMatch,
						'uuid' => $uuid,
						'search_name' =>$search,
						'percent_search' => 100.00,
						'execution_status' => "Registro encontrado",
						'error' => false,
						'count' => count( $data ),
						'class' => 'alert alert-success'
					],
				'code'=>200
            ];
			
			return $result;

        }

        $result =[
			'type'=>'coincidences',
			'result' =>[
					'data'=>$personPublics
			],
			'code'=>200
		];

        return $result;
    }

}