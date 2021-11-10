<?php

namespace App\Http\Controllers;

use App\Doc;
use App\Doccomment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DoccommentController extends Controller
{

    public function index(Request $request)
    {
        $doc_id = $request->doc_id;
        $doc = Doc::findOrFail($doc_id);

        $log=false;
        if($request->has('log')){
            $log=true;
        }
        $doccomments=$doc->doccomments()->orderBy('id','desc')->get();;
        return view('doccomments.index', [
            'doc' => $doc,
            'doccomments'=>$doccomments,
            'log'=>$log
        ]);

    }


    public function create()
    {

    }


    public function store(Request $request)
    {
        $comment=$request->comment;
        $user=Auth::user();

        if($request->has('status')){

            $doc = Doc::findOrFail($request->doc_id);
            $doc->status=2;
            $doc->end=Carbon::now();
            $doc->save();
        }

        $doccomment=new Doccomment();
        $doccomment->comment=$comment;
        $doccomment->doc_id=$request->doc_id;
        $doccomment->save();

        return back()->with('success', 'Comment Add');

    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
