<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class SerialController extends Controller
{
    public function copy_serial(Request $request){
        $serials=$request->serials;
        \DB::table('serial_copy')->insert(
            ['serials' => json_encode($serials), 'created_at'=>Carbon::now()]
        );
    }
}
