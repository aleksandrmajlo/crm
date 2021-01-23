<?php

namespace App\Http\Controllers;

use App\Services\Logwrite;
use Illuminate\Http\Request;

class LogController extends Controller
{
   public function ajax(Request $request){
       $results = [];
       $delete=$request->delete;
       $orderlogs = Logwrite::write($request->task_id,true,$delete);
       return response()->json([
           'success' => true,
           'orderlogs' => $orderlogs,
       ], 200);
   }
}
