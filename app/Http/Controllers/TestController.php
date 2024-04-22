<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller{

    
    function test(Request $req){
        return 'server is working fine';
        // return response()->json(['data'=>'working fine']);
    }



}