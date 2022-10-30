<?php

namespace App\Imports;

use App\PersonPublic;
use Maatwebsite\Excel\Concerns\ToModel;

class PersonPublicImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PersonPublic([
            'department'=>$row[1],
            'location'=>$row[2],
            'municipality'=>$row[3],
            'name'=>$row[4],
            'active_years'=>$row[5],
            'person_type'=>$row[6],
            'type_of_load'=>$row[7],
        ]);
    }
}
