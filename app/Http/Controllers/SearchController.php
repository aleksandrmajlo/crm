<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Serial;
use App\User;

class SearchController extends Controller
{
     public function index(Request $request){

         $data=[
             'title'=>trans('search.title'),
             'meta_title'=>trans('search.meta_title'),
             'with_sidebar'=>false,
             'with_content'=>'12',
             'value'=>'',
             'status'=>config('adm_settings.statusTask'),
             'serial'=>false
         ];
         if($request->has('q')){
             $q= $name = $request->input('q');
             $data['value']=$q;
             $serial=Serial::where('serial', 'LIKE', "%$q%")->first();
             $data['serial']=$serial;
             if($serial){
                 $data['serial_other']=Serial::where('order_id', $serial->order_id)
                     ->where('id', '!=',  $serial->id )
                     ->get();
             }
             $users=User::where('role','3')
                          -> pluck('color','id' );

             $data['users']=$users;
         }
         return view('search',$data);

     }
}
