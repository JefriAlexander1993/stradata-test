<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class PersonPublicExport implements FromView
{
    protected  $personPublic;
    private  $fileName ;
    public function __construct($personPublic,$fileName )
    {
        $this->personPublic= $personPublic;
        $this->$fileName =$fileName;
    }

    public function view(): View
    {
        return view('exports.excels.personPublic', [
            'personPublics' =>$this->personPublic
        ]);
    }
}
