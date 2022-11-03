<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // public function __construct(){
    //     $this->middleware('jwtauth');
    // }
    /*
    |-------------------------------------------------------------------------------
    | index
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/admin
    | Method:         Post
    | Description:    Dasboard
    */
    function index(){
       return response()->json(['success'=>true,'message'=>'Your are in the Dasborad']);
    }
}
