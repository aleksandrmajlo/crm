<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index(){
        $home=\App\Infopage::where('alias','help')->first();
        return view('home',[
            'text'=>$home->text,
            'image'=>!empty($home->image) ? $home->image : null ,
            'title'=>$home->h1,
            'meta_title'=>$home->meta_title,
            'meta_description'=>$home->meta_description,
        ]);
    }
}
