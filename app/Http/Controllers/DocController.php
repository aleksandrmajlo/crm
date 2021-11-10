<?php

namespace App\Http\Controllers;

use App\Doc;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DocController extends Controller
{

    public function index(Request $request)
    {
        $status = '';
        if ($request->has('status')) {
            $docs = Doc::orderBy('id', 'desc')->where('status', $request->status)->paginate(20);
            if ($request->status == 1) {
                $status = 'work';
            } else {
                $status = 'end';
            }
        } else {
            $docs = Doc::orderBy('id', 'desc')->paginate(20);
        }
        $users = User::where('role', 3)->get();
        $ar_workers = [];
        foreach ($users as $user) {
            if ($user->docs->isEmpty()) {
                $ar_workers[] = [
                    'user_id' => $user->id,
                    'email' => $user->email
                ];
            } else {
                $count = $user->docs()->where('status', 1)->count();
                if($count==0){
                    $ar_workers[] = [
                        'user_id' => $user->id,
                        'email' => $user->email
                    ];
                }
            }
        }
        return view('docs.index', [
            'docs' => $docs,
            'status' => $status,
            'users' => $ar_workers
        ]);
    }


    /*
     *  список для вывода
     * работнику
     */
    public function index_worker()
    {
        $user = Auth::user();
        $show=false;
        if ($user->docs->isEmpty()) {
            $show=true;
        } else {
            $count = $user->docs()->where('status', 1)->count();
            if($count==0){
                $show=true;;
            }
        }
        $docs = Doc::orderBy('id', 'desc')->active()->paginate(20);
        return view('docs.index_worker', [
            'docs' => $docs,
            'show' => $show
        ]);
    }

    /*
     * список заданий самого работника
     */
    public function index_worker_my(Request $request)
    {
        $user = Auth::user();
        if($request->has('type')){

            if($request->type=='work'){
                $docs=$user->docs()->orderBy('id','desc')->where('status',1)->paginate(20);
            }
            if($request->type=='history'){
                $docs=$user->docs()->orderBy('id','desc')->where('status',2)->paginate(20);
            }

        }else{
            $docs=$user->docs()->orderBy('id','desc')->paginate(20);
        }


        return view('docs.index_worker_my', [
            'user' => $user,
            'docs'=>$docs
        ]);
    }

    public function create()
    {
        //
    }

    /*
     *  создание документа
     */

    public function store(Request $request)
    {


        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $name = $file->getClientOriginalName();
            $text = file_get_contents($file->getRealPath());

            $doc = new Doc();
            $doc->admin_id = Auth::user()->id;
            $doc->name = $name;
            $doc->text = $text;
            if ($request->has('user_id')) {
                $doc->user_id = $request->user_id;
                $doc->start=Carbon::now();
            }
            $doc->save();
        }
        return back()->with('success', 'Add file!');

    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    /*
     * пользователь добавляет себе задание
     */
    public function docs_user_add(Request $request)
    {

        $doc_id = $request->doc_id;
        $user = Auth::user();

        $show=false;
        if ($user->docs->isEmpty()) {
            $show=true;
        } else {
            $count = $user->docs()->where('status', 1)->count();
            if($count==0){
                $show=true;;
            }
        }
        if ($show) {

            $doc = Doc::find($doc_id);
            $doc->start=Carbon::now();
            $doc->save();

            $user->docs()->save($doc);
        }
        return redirect('/docs_user_my')->with('status', 'Doc Add');
    }

}
