<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //

    function success($json ,$statusCode = 200){
        return response()->json(
            [
                'status'=>'success',
                'ret_data'=>$json,
            ])->setStatusCode($statusCode);
    }

    function error($error, $statusCode = 500){
        return response()->json(
            [
                'status'=>'error',
                'ret_data'=>null,
                'error'=> $error
            ])->setStatusCode($statusCode);
    }
}
