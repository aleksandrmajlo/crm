<?php

namespace App\Http\Controllers;

use App\Services\Logwrite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LogController extends Controller
{
   public function ajax(Request $request){
       $results = [];
       $delete=$request->delete;
       $orderlogs = Logwrite::write($request->task_id,true,$delete);
       $count=DB::table('serials')->where('task_id',$request->task_id)
                                       ->count();
       return response()->json([
           'success' => true,
           'orderlogs' => $orderlogs,
           'count'=>$count
       ], 200);
   }
}
