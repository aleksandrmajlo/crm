<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoaccessController extends Controller
{
    public function index(){
        return view('noaccess',[
            'title'=>trans('noaccess.title'),
            'meta_title'=>trans('noaccess.meta_title'),
        ]);
    }
}
