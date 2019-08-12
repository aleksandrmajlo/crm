<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PersonalController extends Controller
{
    public function index()
    {
        return view('task.mytask',[
            'title'=>trans('mytask.title'),
            'meta_title'=>trans('mytask.meta_title'),
        ]);
    }
}
