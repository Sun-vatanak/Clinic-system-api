<?php

namespace App\Http\Controllers;

abstract class Controller
{
      public  function res_suu($msg='',$data=null){
        return response()->json([
            'result' => true,
            'message' => $msg,
            'data' => $data
        ]);
    }
     public function res_paginate($page,$msg='',$data){
        return response()->json([
            'result' => true,
            'message' => $msg,
            'data'=>$data,
           'pagination'=>[
                'total'=>$page->total(),
                // get total number of records
                'total_page'=>$page->lastPage(),
                // get total number of pages
                'current_page'=>$page->currentPage(),
                // get current page number
                'per_page'=>$page->perPage(),
                // get number of records per page
                'has_more'=>$page->hasMorePages(),
                // check if there are more pages
                'has_page'=>$page->hasPages(),
            ],
        ]);

    }

}
