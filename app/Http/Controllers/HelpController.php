<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index(){
        $home=\App\Infopage::where('alias','help')->first();


        $IMAGE_HIDDEN=env("IMAGE_HIDDEN", false);
        $image=!empty($home->image) ? $home->image : null;
        if($IMAGE_HIDDEN){
            $image=false;
        }


        return view('home',[
            'text'=>$home->text,
            'image'=>$image,
            'title'=>$home->h1,
            'meta_title'=>$home->meta_title,
            'meta_description'=>$home->meta_description,
        ]);
    }
}
